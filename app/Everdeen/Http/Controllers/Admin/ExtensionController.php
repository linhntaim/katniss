<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Themes\ExtensionsFacade;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class ExtensionController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'extension';
    }

    public function index(Request $request)
    {
        $this->theme->title(trans('pages.admin_extensions_title'));
        $this->theme->description(trans('pages.admin_extensions_desc'));

        $extensionClasses = ExtensionsFacade::all();
        $extensions = [];
        foreach ($extensionClasses as $extensionClass) {
            $extension = new $extensionClass();
            $extensionName = $extension->getName();
            $extensions[] = [
                'name' => $extensionName,
                'display_name' => $extension->getDisplayName(),
                'description' => $extension->getDescription(),
                'editable' => $extension->isEditable(),
                'activated' => ExtensionsFacade::isActivated($extensionName),
                'static' => ExtensionsFacade::isStatic($extensionName),
            ];
        }

        return $this->_list([
            'extensions' => $extensions,
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    public function edit(Request $request, $name)
    {
        $extensionClass = ExtensionsFacade::extensionClass($name);
        if (empty($extensionClass) || !class_exists($extensionClass) || !isActivatedExtension($name)) {
            abort(404);
        }
        $extension = new $extensionClass();
        if (!$extension->isEditable()) {
            abort(404);
        }

        $this->theme->title([trans('pages.admin_extensions_title'), $extension->getDisplayName(), trans('form.action_edit')]);
        $this->theme->description($extension->getDescription());

        return $this->_edit(array_merge([
            'extension' => $extension,
            'extension_view' => $extension->viewAdmin(),
        ], $extension->viewAdminParams()));
    }

    public function update(Request $request)
    {
        $extensionClass = ExtensionsFacade::extensionClass($request->input('extension'));
        if (empty($extensionClass) || !class_exists($extensionClass)) {
            abort(404);
        }
        $extension = new $extensionClass();
        if (!$extension->isEditable()) {
            abort(404);
        }

        $redirect = redirect(adminUrl('extensions/{name}/edit', ['name' => $extension->getName()]));

        $data = [];
        foreach ($extension->fields() as $field) {
            $data[$field] = $request->input($field, '');
        }
        $validator = Validator::make($data, $extension->validationRules());
        if ($validator->fails()) {
            return $redirect->withInput()->withErrors($validator);
        }

        $translatable = $extension->isTranslatable();
        $localizedData = [];
        if ($translatable) {
            $validateRequest = $this->validateMultipleLocaleInputs($request, $extension->localizedValidationRules());

            if ($validateRequest->isFailed()) {
                return $redirect->withInput()->withErrors($validateRequest->getFailed());
            }

            $localizedData = $validateRequest->getLocalizedInputs();
        }

        $save = $extension->save($data, $localizedData);
        if ($save !== true) {
            return $redirect->withInput()->withErrors($save);
        }

        return $redirect;
    }

    public function activate(Request $request, $name)
    {
        $extensionClasses = array_keys(ExtensionsFacade::all());
        $activatedExtensions = activatedExtensions();
        if (in_array($name, $extensionClasses) && !in_array($name, $activatedExtensions)) {
            $activatedExtensions[] = $name;
            setOption('activated_extensions', $activatedExtensions, 'man:extensions');
        }

        $redirect_url = adminUrl('extensions');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        return redirect($redirect_url);
    }

    public function deactivate(Request $request, $name)
    {
        $extensionClasses = array_keys(ExtensionsFacade::all());
        $activatedExtensions = activatedExtensions();
        if (in_array($name, $extensionClasses) && in_array($name, $activatedExtensions)) {
            $activatedExtensions = array_diff($activatedExtensions, [$name]);
            setOption('activated_extensions', $activatedExtensions, 'man:extensions');
        }

        $redirect_url = adminUrl('extensions');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        return redirect($redirect_url);
    }
}
