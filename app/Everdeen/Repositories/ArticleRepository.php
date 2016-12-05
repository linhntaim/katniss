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

class ArticleRepository extends PostRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Post::ARTICLE, $id);
    }

    public function create($userId, $template = null, $featuredImage = null, array $localizedData = [], array $categories = [])
    {
        DB::beginTransaction();
        try {
            $article = new Post();
            $article->type = Post::ARTICLE;
            $article->user_id = $userId;
            $article->template = $template;
            $article->featured_image = $featuredImage;
            foreach ($localizedData as $locale => $transData) {
                $trans = $article->translateOrNew($locale);
                $trans->title = $transData['title'];
                $trans->slug = $transData['slug'];
                $trans->description = $transData['description'];
                $trans->content = clean($transData['content'], 'blog');
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