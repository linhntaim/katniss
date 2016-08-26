<?php

namespace Katniss\Http\Controllers;

use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\AppOptionHelper;

class KatnissController extends Controller
{
    /**
     * @var boolean
     */
    public $isAuth;

    /**
     * @var \Katniss\Models\User
     */
    public $authUser;

    /**
     * @var string
     */
    public $localeCode;

    protected $validationErrors;

    public function __construct(Request $request)
    {
        AppOptionHelper::load();

        $this->localeCode = currentLocaleCode();
        $this->isAuth = isAuth();
        $this->authUser = authUser();
        $this->validationErrors = collect([]);

        if ($this->isAuth) {
            $own_directory = $this->authUser->ownDirectory;
            config(['katniss.disks.' . $own_directory => [
                'driver' => 'local',
                'root' => storage_path('../public/upload/file_manager/users/' . $own_directory),
            ]]);
        }
    }

    protected function htmlInputs(Request $request)
    {
        $tmpHtmlInputs = $request->input(AppConfig::KEY_HTML_INPUTS, '');
        $htmlInputs = [];
        if (!empty($tmpHtmlInputs)) {
            $tmpHtmlInputs = explode(',', $tmpHtmlInputs);
            foreach ($tmpHtmlInputs as $tmpHtmlInput) {
                $tmpHtmlInput = explode('|', $tmpHtmlInput);
                $htmlInputs[$tmpHtmlInput[0]] = empty($tmpHtmlInput[1]) ? AppConfig::DEFAULT_HTML_CLEAN_SETTING : $tmpHtmlInput[1];
            }
        }
        return $htmlInputs;
    }

    public function validateMultipleLocaleData(Request $request, array $fieldNames, array $rules, &$data, &$successes, &$fails, &$old, array $htmlInputs = [])
    {
        $emptyHtmlInputs = empty($htmlInputs);
        $allSupportedLocaleCodes = allSupportedLocaleCodes();
        $data = [];
        $successes = [];
        $fails = [];
        $old = [];

        foreach ($fieldNames as $fieldName) {
            $input = $request->input($fieldName);
            if (empty($input)) {
                $input = $request->file($fieldName);
            }
            if (empty($input)) continue;
            foreach ($input as $locale => $value) {
                if (!in_array($locale, $allSupportedLocaleCodes)) {
                    continue;
                }
                if (!isset($data[$locale])) {
                    $data[$locale] = [];
                }
                if (!$emptyHtmlInputs && array_key_exists($fieldName, $htmlInputs)) {
                    $value = clean($value, $htmlInputs[$fieldName]);
                }
                $data[$locale][$fieldName] = $value;
                $old[$fieldName . '_' . $locale] = $value;
            }
        }

        if (empty($rules)) {
            $successes[] = array_keys($data);
            return;
        }
        foreach ($data as $locale => $inputs) {
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) {
                $fails[] = $validator;
            } else {
                $successes[] = $locale;
            }
        }
    }

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->validationErrors = collect([]);
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->validationErrors = $validator->errors();
            return false;
        }

        return true;
    }

    protected function getValidationErrors()
    {
        return $this->validationErrors->all();
    }

    protected function getFirstValidationError()
    {
        return $this->validationErrors->first();
    }
}
