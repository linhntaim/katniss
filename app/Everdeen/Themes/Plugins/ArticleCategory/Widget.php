<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 16:16
 */

namespace Katniss\Everdeen\Themes\Plugins\ArticleCategory;

use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\ArticleCategoryRepository;
use Katniss\Everdeen\Themes\Widget as BaseWidget;

class Widget extends BaseWidget
{
    const NAME = 'article_category';
    const DISPLAY_NAME = 'Article Category';

    protected $image;
    protected $categoryId;
    protected $showButton;

    protected function __init()
    {
        parent::__init();

        $this->image = defPr($this->getProperty('image'), '');
        $this->categoryId = defPr($this->getProperty('category_id'), '');
        $this->showButton = defPr($this->getProperty('show_button'), 0);
    }

    public function viewAdminParams()
    {
        $categoryRepository = new ArticleCategoryRepository();

        return array_merge(parent::viewAdminParams(), [
            'image' => $this->image,
            'category_id' => $this->categoryId,
            'categories' => $categoryRepository->getAll(),
            'show_button' => $this->showButton,
        ]);
    }

    public function viewHomeParams()
    {
        $category = null;
        if (!empty($this->categoryId)) {
            $categoryRepository = new ArticleCategoryRepository();
            $category = $categoryRepository->getById($this->categoryId);
        }
        return array_merge(parent::viewHomeParams(), [
            'image' => $this->image,
            'category' => $category,
            'show_button' => $this->showButton,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'image',
            'category_id',
            'show_button',
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'image' => 'sometimes|url',
            'category_id' => 'required|exists:categories,id,type,' . Category::TYPE_ARTICLE,
            'show_button' => 'sometimes|in:1',
        ]);
    }
}