<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Everdeen\Models\ThemeWidget;
use Katniss\Everdeen\Themes\WidgetsFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WidgetController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'widget';
    }

    public function index(Request $request)
    {
        $widgetClasses = WidgetsFacade::all();
        asort($widgetClasses);
        $widgets = [];
        foreach ($widgetClasses as $widgetClass) {
            $widget = new $widgetClass();
            $widgets[$widget->getName()] = $widget->getDisplayName();
        }

        $placeholders = HomeThemeFacade::placeholders();
        asort($placeholders);
        $placeholderNames = array_keys($placeholders);
        $themeWidgets = ThemeWidget::checkPlaceholders($placeholderNames)
            ->checkWidgets(array_keys($widgets))
            ->orderBy('order', 'asc')->orderBy('created_at', 'asc')->get();
        $themePlaceholders = [];
        foreach ($placeholderNames as $placeholderName) {
            $themePlaceholders[$placeholderName] = [];
        }
        foreach ($themeWidgets as $themeWidget) {
            $placeholderName = $themeWidget->placeholder;
            $themePlaceholders[$placeholderName][] = $themeWidget;
        }

        $this->theme->title(trans('pages.admin_widgets_title'));
        $this->theme->description(trans('pages.admin_widgets_desc'));

        return $this->_list([
            'widgets' => $widgets,
            'placeholders' => $placeholders,
            'placeholderNames' => $placeholderNames,
            'themePlaceholders' => $themePlaceholders,
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'widget' => 'required|in:' . implode(',', array_keys(WidgetsFacade::all())),
            'placeholder' => 'required|in:' . implode(',', array_keys(HomeThemeFacade::placeholders())),
        ]);

        $redirect = redirect(adminUrl('widgets'));
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator);
        }

        $widget = $request->input('widget');
        $widgetClass = WidgetsFacade::widgetClass($widget);
        if (empty($widgetClass) || !class_exists($widgetClass)) {
            abort(404);
        }
        $widget = new $widgetClass();
        $widget->create($request->input('placeholder'));

        return $redirect;
    }

    public function edit(Request $request, $id)
    {
        $themeWidget = ThemeWidget::findOrFail($id);
        $widgetClass = WidgetsFacade::widgetClass($themeWidget->name);
        if (empty($widgetClass) || !class_exists($widgetClass)) {
            abort(404);
        }
        $params = empty($themeWidget) ? [] : $themeWidget->params;
        $widget = new $widgetClass($params);
        $widget->setThemeWidget($themeWidget);

        $this->theme->title([trans('pages.admin_widgets_title'), $widget->getDisplayName(), trans('form.action_edit')]);
        $this->theme->description(trans('pages.admin_widgets_desc'));

        return $this->_edit(array_merge([
            'widget' => $widget,
            'themeWidget' => $themeWidget,
            'widget_view' => $widget->viewAdmin(),
        ], $widget->viewAdminParams()));
    }

    public function update(Request $request)
    {
        $themeWidget = ThemeWidget::findOrFail($request->input('id'));
        $widgetClass = WidgetsFacade::widgetClass($themeWidget->name);
        if (empty($widgetClass) || !class_exists($widgetClass)) {
            abort(404);
        }
        $params = empty($themeWidget) ? [] : $themeWidget->params;
        $widget = new $widgetClass($params);
        $widget->setThemeWidget($themeWidget);

        $redirect = redirect(adminUrl('widgets/{id}/edit', ['id' => $themeWidget->id]));

        $data = [];
        foreach ($widget->fields() as $field) {
            $data[$field] = $request->input($field, '');
        }
        $validator = Validator::make($data, $widget->validationRules());
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator);
        }

        $translatable = $widget->isTranslatable();
        $localizedData = [];
        if ($translatable) {
            $validateResult = $this->validateMultipleLocaleInputs($request, $widget->localizedValidationRules());

            if ($validateResult->isFailed()) {
                return $redirect->withInput()->withErrors($validateResult->getFailed());
            }

            $localizedData = $validateResult->getLocalizedInputs();
        }

        $save = $widget->update($data, $localizedData);
        if ($save !== true) {
            return $redirect->withInput()->withErrors($save);
        }

        return $redirect;
    }

    public function activate(Request $request, $id)
    {
        $themeWidget = ThemeWidget::findOrFail($id);

        $redirect_url = adminUrl('widgets');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }
        $themeWidget->active = true;

        return $themeWidget->save() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }

    public function deactivate(Request $request, $id)
    {
        $themeWidget = ThemeWidget::findOrFail($id);

        $redirect_url = adminUrl('widgets');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        $themeWidget->active = false;

        return $themeWidget->save() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }

    public function destroy(Request $request, $id)
    {
        $themeWidget = ThemeWidget::findOrFail($id);

        $redirect_url = adminUrl('widgets');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        return $themeWidget->delete() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }

    public function copyTo(Request $request)
    {
        $themeWidget = ThemeWidget::findOrFail($request->input('widget_id'));

        $validator = Validator::make($request->all(), [
            'placeholder' => 'required|in:' . implode(',', array_keys(HomeThemeFacade::placeholders())),
        ]);

        $redirect = redirect(adminUrl('widgets'));
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        ThemeWidget::create([
            'widget_name' => $themeWidget->widget_name,
            'theme_name' => $themeWidget->theme_name,
            'placeholder' => $request->input('placeholder'),
            'constructing_data' => $themeWidget->constructing_data,
            'active' => $themeWidget->active,
            'order' => ThemeWidget::where('placeholder', $request->input('placeholder'))->count() + 1,
        ]);

        return $redirect;
    }
}
