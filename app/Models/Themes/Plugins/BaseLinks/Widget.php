<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 16:16
 */

namespace Katniss\Models\Themes\Plugins\BaseLinks;

use Katniss\Models\Category;

;
use Katniss\Models\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;

class Widget extends DefaultWidget
{
    const WIDGET_NAME = 'base_links';
    const WIDGET_DISPLAY_NAME = 'Base Links';

    protected $category_id = '';

    protected function __init()
    {
        parent::__init();

        $this->category_id = defPr($this->getProperty('category_id'), '');
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'category_id' => $this->category_id,
            'categories' => Category::where('type', Category::LINK)->get(),
        ]);
    }

    public function viewHomeParams()
    {
        $links = empty($this->category_id) ? collect([]) : Category::findOrFail($this->category_id)->links;
        return array_merge(parent::viewHomeParams(), [
            'name' => $this->name,
            'links' => $links,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'category_id'
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'category_id' => 'required|exists:categories,id,type,' . Category::LINK,
        ]);
    }
}