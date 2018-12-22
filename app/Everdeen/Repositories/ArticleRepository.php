<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 18:35
 */

namespace Katniss\Everdeen\Repositories;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Post;
use Katniss\Everdeen\Themes\Extension;
use Katniss\Everdeen\Themes\Plugins\AppSettings\Extension as AppSettingsExtension;
use Katniss\Everdeen\Utils\AppConfig;

class ArticleRepository extends PostRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Post::TYPE_ARTICLE, $id);
    }

    public function getSearchPaged($title = null, $author = null, $categories = null)
    {
        $posts = Post::with(['translations', 'categories', 'categories.translations', 'author'])
            ->where('type', $this->type);

        if (!empty($title)) {
            $posts->whereTranslationLike('title', '%' . $title . '%');
        }
        if (!empty($author)) {
            $posts->where('user_id', $author);
        }
        if (!empty($categories)) {
            $posts->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('id', $categories);
            });
        }

        return $posts->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getPagedByCategory($category)
    {
        $posts = Post::with(['translations', 'author'])
            ->where('type', $this->type)
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('id', $category->id);
            });

        return $posts->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function create($userId, $template = null, $featuredImage = null,
                           array $localizedData = [], array $categories = [])
    {
        DB::beginTransaction();
        try {
            $article = new Post();
            $article->type = $this->type;
            $article->user_id = $userId;
            $article->template = $template;
            $article->featured_image = $featuredImage;
            foreach ($localizedData as $locale => $transData) {
                $trans = $article->translateOrNew($locale);
                $trans->title = $transData['title'];
                $trans->slug = $transData['slug'];
                $trans->description = $transData['description'];
                $trans->content = clean($transData['content'], 'blog');
                $trans->raw_content = $transData['content'];
            }

            $article->save();

            if (count($categories) > 0) {
                $article->categories()->attach($categories);
            } else {
                $appSettings = Extension::getSharedData(AppSettingsExtension::NAME);
                $article->categories()->attach([$appSettings->defaultArticleCategory]);
            }

            DB::commit();

            return $article;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($userId, $template = null, $featuredImage = null, array $localizedData = [], array $categories = [])
    {
        $article = $this->model();
        $article->template = $template;
        $article->featured_image = $featuredImage;

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $article->translateOrNew($locale);
                    $trans->title = $transData['title'];
                    $trans->slug = $transData['slug'];
                    $trans->description = $transData['description'];
                    $trans->content = clean($transData['content'], 'blog');
                    $trans->raw_content = $transData['content'];
                } elseif ($article->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            if (count($categories) > 0) {
                $article->categories()->sync($categories);
            } else {
                $appSettings = Extension::getSharedData(AppSettingsExtension::NAME);
                $article->categories()->sync([$appSettings->defaultArticleCategory]);
            }

            $article->save();

            if (!empty($deletedLocales)) {
                $article->deleteTranslations($deletedLocales);
            }
            DB::commit();

            return $article;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function view()
    {
        $article = $this->model();
        try {
            $article->increment('viewed');
            return true;
        } catch (\Exception $exception) {
            throw new KatnissException(trans('error.database_update') . ' (' . $exception->getMessage() . ')');
        }
    }
}