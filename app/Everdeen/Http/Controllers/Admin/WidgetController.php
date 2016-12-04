<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Repositories\ThemeWidgetRepository;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Everdeen\Models\ThemeWidget;
use Katniss\Everdeen\Themes\WidgetsFacade;

class WidgetController extends ViewController
{
    protected $widgetRepository;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'widget';
        $this->widgetRepository = new ThemeWidgetRepository($request->input('id'));
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
        $this->widgetRepository->model($id);

        $this->_rdrUrl($request, adminUrl('widgets'), $rdrUrl, $errorRdrUrl);

        try {
            $this->widgetRepository->updateActive();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function deactivate(Request $request, $id)
    {
        $this->widgetRepository->model($id);

        $this->_rdrUrl($request, adminUrl('widgets'), $rdrUrl, $errorRdrUrl);

        try {
            $this->widgetRepository->updateActive(false);
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function destroy(Request $request, $id)
    {
        $this->widgetRepository->model($id);

        $this->_rdrUrl($request, adminUrl('widgets'), $rdrUrl, $errorRdrUrl);

        try {
            $this->widgetRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function copyTo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placeholder' => 'required|in:' . implode(',', array_keys(HomeThemeFacade::placeholders())),
        ]);

        $redirect = redirect(adminUrl('widgets'));
        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        $this->widgetRepository->duplicate($request->input('placeholder'));

        return $redirect;
    }
}
