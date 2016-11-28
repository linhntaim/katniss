<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 10:03
 */

namespace Katniss\Everdeen\Themes;

use Katniss\Everdeen\Models\ThemeWidget;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Everdeen\Utils\AppConfig;

abstract class Widget extends Plugin
{
    public static function doRender(ThemeWidget $themeWidget)
    {
        $widgetClass = WidgetsFacade::widgetClass($themeWidget->name);
        if (!empty($widgetClass) && class_exists($widgetClass)) {
            $params = empty($themeWidget) ? [] : $themeWidget->params;
            $widget = new $widgetClass($params);
            $widget->setThemeWidget($themeWidget);
            return $widget->render();
        }
        return '';
    }

    public static function doRegister(ThemeWidget $themeWidget)
    {
        $widgetClass = WidgetsFacade::widgetClass($themeWidget->name);
        if (!empty($widgetClass) && class_exists($widgetClass)) {
            $params = empty($themeWidget) ? [] : $themeWidget->params;
            $widget = new $widgetClass($params);
            $widget->setThemeWidget($themeWidget);
            $widget->register();
        }
    }

    /**
     * @var ThemeWidget
     */
    protected $themeWidget;

    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->fromDataConstruct($data);

        $this->__init();
    }

    public function setId($id)
    {
        $this->themeWidget = ThemeWidget::findOrFail($id);
    }

    public function getId()
    {
        return empty($this->themeWidget) ? $this::NAME : $this->themeWidget->id;
    }

    public function getHtmlId()
    {
        return 'widget-' . $this->getId();
    }

    /**
     * @param ThemeWidget $themeWidget
     */
    public function setThemeWidget($themeWidget)
    {
        $this->themeWidget = $themeWidget;
    }

    public function viewAdmin()
    {
        return empty($this::THEME_NAME) ?
            HomeThemeFacade::commonAdminWidget($this::NAME) : HomeThemeFacade::adminWidget($this::NAME);
    }

    public function viewAdminParams()
    {
        return [
            'html_id' => $this->getHtmlId(),
        ];
    }

    public function viewHome()
    {
        return empty($this::THEME_NAME) ?
            HomeThemeFacade::commonWidget($this::NAME) : HomeThemeFacade::widget($this::NAME);
    }

    public function viewHomeParams()
    {
        return [
            'html_id' => $this->getHtmlId(),
        ];
    }

    protected function renderByTemplate()
    {
        return view()->make($this->viewHome(), $this->viewHomeParams())->render();
    }

    public function render()
    {
        return '';
    }

    public function save($placeholder, array $data = [], array  $localizedData = [])
    {
        if (empty($this->themeWidget)) {
            return $this->create($placeholder, $data, $localizedData);
        } else {
            return $this->update($data, $localizedData);
        }
    }

    public function create($placeholder, array $data = [], array $localizedData = [])
    {
        if (!$this::TRANSLATABLE) {
            $localizedData = [];
        }

        $order = ThemeWidget::where('placeholder', $placeholder)->count() + 1;
        $this->themeWidget = ThemeWidget::create([
            'widget_name' => $this::NAME,
            'theme_name' => $this::THEME_NAME,
            'placeholder' => $placeholder,
            'constructing_data' => $this->toDataConstructAsJson($data, $localizedData),
            'order' => $order,
        ]);
        return empty($this->themeWidget) ? [trans('error.database_insert')] : true;
    }

    public function update(array $data = [], array $localizedData = [])
    {
        $this->themeWidget->constructing_data = $this->toDataConstructAsJson($data, $localizedData);
        if ($this->themeWidget->save() === true) {
            return true;
        }

        return [trans('error.database_update')];
    }
}