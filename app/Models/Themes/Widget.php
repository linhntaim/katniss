<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 10:03
 */

namespace Katniss\Models\Themes;

use Katniss\Models\Themes\HomeThemes\HomeThemeFacade;

abstract class Widget
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

    const WIDGET_NAME = '';
    const WIDGET_DISPLAY_NAME = '';
    const WIDGET_DESCRIPTION = '';
    const WIDGET_TRANSLATABLE = false;
    const THEME_NAME = '';

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $localizedData;

    /**
     * @var ThemeWidget
     */
    protected $themeWidget;

    /**
     * @var array
     */
    protected $params;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->params = [];

        $this->__init();
    }

    public function isTranslatable()
    {
        return $this::WIDGET_TRANSLATABLE;
    }

    public function setId($id)
    {
        $this->themeWidget = ThemeWidget::findOrFail($id);
    }

    public function getId()
    {
        return empty($this->themeWidget) ? $this::WIDGET_NAME : $this->themeWidget->id;
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

    public function getName()
    {
        return $this::WIDGET_NAME;
    }

    public function getDisplayName()
    {
        return $this::WIDGET_DISPLAY_NAME;
    }

    public function getDescription()
    {
        return $this::WIDGET_DESCRIPTION;
    }

    public function getTheme()
    {
        return $this::THEME_NAME;
    }

    public function getProperty($name, $locale = '')
    {
        if (empty($locale) || !$this::WIDGET_TRANSLATABLE) {
            return !empty($this->data[$name]) ? $this->data[$name] :
                (!empty($this->localizedData[$name]) ? $this->localizedData[$name] : '');
        }

        if (!isset($this->data[$locale])) return '';

        return !empty($this->data[$locale][$name]) ? $this->data[$locale][$name] : '';
    }

    public function register()
    {
    }

    protected function __init()
    {
        if ($this::WIDGET_TRANSLATABLE) {
            $locale = currentLocale();
            $fallbackLocale = config('app.fallback_locale');

            $this->localizedData = null;
            if (!empty($this->data[$locale])) {
                $this->localizedData = $this->data[$locale];
            } elseif (!empty($this->data[$fallbackLocale])) {
                $this->localizedData = $this->data[$fallbackLocale];
            }
        }
    }

    public function viewAdmin()
    {
        return empty($this::THEME_NAME) ? HomeThemeFacade::commonAdminWidget($this::WIDGET_NAME) : HomeThemeFacade::adminWidget($this::WIDGET_NAME);
    }

    public function viewAdminParams()
    {
        return [
            'html_id' => $this->getHtmlId(),
        ];
    }

    public function viewHome()
    {
        return empty($this::THEME_NAME) ? HomeThemeFacade::commonWidget($this::WIDGET_NAME) : HomeThemeFacade::widget($this::WIDGET_NAME);
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

    public function validationRules()
    {
        return [];
    }

    public function localizedValidationRules()
    {
        if (!$this::WIDGET_TRANSLATABLE) abort(404);

        return [];
    }

    public function fields()
    {
        return [];
    }

    public function localizedFields()
    {
        if (!$this::WIDGET_TRANSLATABLE) abort(404);

        return [];
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
        if (!$this::WIDGET_TRANSLATABLE) {
            $localizedData = [];
        }

        $this->themeWidget = ThemeWidget::create([
            'widget_name' => $this::WIDGET_NAME,
            'theme_name' => $this::THEME_NAME,
            'placeholder' => $placeholder,
            'translatable' => $this::WIDGET_TRANSLATABLE,
            'constructing_data' => json_encode(array_merge($data, $localizedData))
        ]);
        return empty($this->themeWidget) ? [trans('error.database_insert')] : true;
    }

    public function update(array $data = [], array $localizedData = [])
    {
        $this->themeWidget->constructing_data = json_encode(array_merge($data, $localizedData));
        if ($this->themeWidget->save() === true) {
            return true;
        }

        return [trans('error.database_update')];
    }
}