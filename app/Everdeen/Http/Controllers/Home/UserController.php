<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:05
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\UserRepository;

class UserController extends ViewController
{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'user';
        $this->userRepository = new UserRepository();
    }

    public function signUp(Request $request)
    {
        if ($request->isAuth() && $request->authUser()->hasRole(['teacher', 'student'])) {
            return redirect(homeUrl());
        }

        return $this->_any('sign_up');
    }

    public function getAccountInformation(Request $request)
    {
        return $this->_any('account_information');
    }
}