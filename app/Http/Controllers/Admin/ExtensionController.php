<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Http\Controllers\MultipleLocaleContentController;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Themes\ExtensionsFacade;
use Illuminate\Http\Request;

use Katniss\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ExtensionController extends MultipleLocaleContentController
{
    public function index(Request $request)
    {
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

        return view($this->themePage('extension.list'), [
            'extensions' => $extensions,
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    public function edit(Request $request, $name)
    {
        $extensionClass = ExtensionsFacade::extensionClass($name);
        if (empty($extensionClass) || !class_exists($extensionClass)) {
            abort(404);
        }
        $extension = new $extensionClass();
        if (!$extension->isEditable()) {
            abort(404);
        }

        return view($this->themePage('extension.edit'), array_merge([
            'extension' => $extension,
            'extension_view' => $extension->getAdminViewPath(),
        ], $extension->getAdminViewParams()));
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
            $this->validateMultipleLocaleData($request, $extension->localizedFields(), $extension->localizedValidationRules(), $localizedData, $successes, $fails, $old);

            if (count($successes) <= 0 && count($fails) > 0) {
                return $redirect->withInput()->withErrors($fails[0]);
            }
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
            setAppOption('activated_extensions', $activatedExtensions);
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
            setAppOption('activated_extensions', $activatedExtensions);
        }

        $redirect_url = adminUrl('extensions');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        return redirect($redirect_url);
    }
}
