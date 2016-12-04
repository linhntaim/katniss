<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;


use Illuminate\Support\Facades\DB;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Utils\AppConfig;

class ArticleCategoryRepository extends ModelRepository
{
    public function getById($id)
    {
        return Category::where('id', $id)->where('type', Category::ARTICLE)->firstOrFail();
    }

    public function getPaged()
    {
        return Category::where('type', Category::ARTICLE)
            ->orderBy('created_at', 'desc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Category::where('type', Category::ARTICLE)->get();
    }

    public function create($parentId, array $localizedData = [])
    {
        DB::beginTransaction();
        try {
            $category = new Category();
            $category->type = Category::ARTICLE;
            if ($parentId != 0) {
                $category->parent_id = $parentId;
            }
            foreach ($localizedData as $locale => $transData) {
                $trans = $category->translateOrNew($locale);
                $trans->name = $transData['name'];
                $trans->slug = $transData['slug'];
                $trans->description = $transData['description'];
            }
            $category->save();

            DB::commit();

            return $category;
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new KatnissException(trans('error.database_insert') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function update($parent_id, array $localizedData = [])
    {
        $category = $this->model();
        $category->parent_id = $parent_id != 0 && $parent_id !== $category->parent_id ? $parent_id : null;

        DB::beginTransaction();
        try {
            $deletedLocales = [];
            foreach (supportedLocaleCodesOfInputTabs() as $locale) {
                if (isset($localizedData[$locale])) {
                    $transData = $localizedData[$locale];
                    $trans = $category->translateOrNew($locale);
                    $trans->name = $transData['name'];
                    $trans->slug = $transData['slug'];
                    $trans->description = $transData['description'];
                } elseif ($category->hasTranslation($locale)) {
                    $deletedLocales[] = $locale;
                }
            }

            $category->save();

            if (!empty($deletedLocales)) {
                $category->deleteTranslations($deletedLocales);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_update') . ' (' . $ex->getMessage() . ')');
        }
    }

    public function delete()
    {
        $category = $this->model();
        if ($category->articles()->count() > 0) {
            throw new KatnissException(trans('error.category_not_empty'));
        }

        DB::beginTransaction();
        try {
            Category::where('parent_id', $category->id)->update(['parent_id' => null]);
            $category->delete();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();

            throw new KatnissException(trans('error.database_delete') . ' (' . $ex->getMessage() . ')');
        }
    }
}