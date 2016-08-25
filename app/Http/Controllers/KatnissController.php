<?php

namespace Katniss\Http\Controllers;

use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Http\Request;

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
