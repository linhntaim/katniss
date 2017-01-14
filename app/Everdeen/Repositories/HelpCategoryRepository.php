<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:34
 */

namespace Katniss\Everdeen\Repositories;


use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Themes\Extension;
use Katniss\Everdeen\Themes\Plugins\AppSettings\Extension as AppSettingsExtension;
use Katniss\Everdeen\Utils\AppConfig;

class HelpCategoryRepository extends CategoryRepository
{
    public function __construct($id = null)
    {
        parent::__construct(Category::TYPE_HELP, $id);
    }

    public function getPaged()
    {
        return Category::where('type', $this->type)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
    }

    public function getAll()
    {
        return Category::where('type', $this->type)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function delete()
    {
        $category = $this->model();
        if ($category->posts()->count() > 0) {
            throw new KatnissException(trans('error.category_not_empty'));
        }

        return parent::delete();
    }
}