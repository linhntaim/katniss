<?php

namespace Katniss\Http\Controllers;

use Illuminate\Http\Request;

use Katniss\Http\Requests;
use Katniss\Http\Controllers\Controller;
use Katniss\Models\Helpers\AppOptionHelper;

class KatnissController extends Controller
{
    /**
     * @var boolean
     */
    public $is_auth;

    /**
     * @var \Katniss\Models\User
     */
    public $auth_user;

    /**
     * @var string
     */
    public $locale;

    public function __construct(Request $request)
    {
        AppOptionHelper::load();

        $this->locale = currentLocale();
        $this->is_auth = isAuth();
        $this->auth_user = authUser();

        if ($this->is_auth) {
            $own_directory = $this->auth_user->ownDirectory;
            config(['katniss.disks.' . $own_directory => [
                'driver' => 'local',
                'root' => storage_path('../public/upload/file_manager/users/' . $own_directory),
            ]]);
        }
    }
}
