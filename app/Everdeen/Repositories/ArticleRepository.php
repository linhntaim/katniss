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

    public function hasSlug($slug)
    {
        return Post::whereTranslation('slug', $slug)->count() > 0;
    }

    public function getBySlug($slug)
    {
        return Post::where('type', $this->type)
            ->whereTranslation('slug', $slug)
            ->firstOrFail();
    }

    public function getPublishedBySlug($slug)
    {
        return Post::where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->whereTranslation('slug', $slug)
            ->firstOrFail();
    }

    public function getLast($count = 1)
    {
        $posts = Post::where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->take($count);
        return $count == 1 ? $posts->first() : $posts->get();
    }

    public function getPublishedPaged()
    {
        return Post::where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getSearchPublishedPaged($title = null, $author = null, $categories = null)
    {
        $posts = Post::where('type', $this->type)->where('status', Post::STATUS_PUBLISHED);

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

    public function getTeacherEditingPaged()
    {
        return Post::where('type', $this->type)
            ->where('status', Post::STATUS_TEACHER_EDITING)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getPagedByCategory($categoryId, &$category)
    {
        $categoryRepository = new ArticleCategoryRepository();
        $category = $categoryRepository->getById($categoryId);
        return $category->posts()->where('type', $this->type)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getPublishedPagedByCategory($categoryId, &$category)
    {
        $categoryRepository = new ArticleCategoryRepository();
        $category = $categoryRepository->getById($categoryId);
        return $category->posts()->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getPublishedPagedByCategorySlug($categorySlug, &$category)
    {
        $categoryRepository = new ArticleCategoryRepository();
        $category = $categoryRepository->getBySlug($categorySlug);
        return $category->posts()->where('type', $this->type)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getPublishedPagedByAuthor($authorId, &$author)
    {
        $userRepository = new UserRepository($authorId);
        $author = $userRepository->model();
        return Post::where('type', $this->type)
            ->where('user_id', $author->id)
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getPagedByAuthor($authorId, &$author)
    {
        $userRepository = new UserRepository($authorId);
        $author = $userRepository->model();
        return Post::where('type', $this->type)
            ->where('user_id', $author->id)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function create($userId, $template = null, $featuredImage = null,
                           array $localizedData = [], array $categories = [],
                           $status = Post::STATUS_PUBLISHED)
    {
        DB::beginTransaction();
        try {
            $article = new Post();
            $article->type = $this->type;
            $article->user_id = $userId;
            $article->template = $template;
            $article->featured_image = $featuredImage;
            $article->status = $status;
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
        $article->user_id = $userId;
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
}