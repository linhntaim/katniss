<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-04
 * Time: 20:05
 */

namespace Katniss\Everdeen\Http\Controllers\Home;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;

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

    public function getUserInformation(Request $request)
    {
        return $this->_any('user_information', [
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    public function updateUserInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|max:255',
            'date_of_birth' => 'sometimes|date_format:' . DateTimeHelper::shortDateFormat(),
            'gender' => 'required|in:' . implode(',', allGenders()),
            'phone_code' => 'required|in:' . implode(',', allCountryCodes()),
            'phone_number' => 'required|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'country' => 'required|in:' . implode(',', allCountryCodes()),
            'nationality' => 'required|in:' . implode(',', allCountryCodes()),
        ]);;

        $errorRdr = redirect(homeUrl('profile/user-information'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $userRepository = new UserRepository($request->authUser());
        $settings = settings();

        try {
            $userRepository->updateAttributes([
                'display_name' => $request->input('display_name'),
                'date_of_birth' => DateTimeHelper::getInstance()
                    ->convertToDatabaseFormat(DateTimeHelper::shortDateFormat(), $request->input('date_of_birth'), true),
                'gender' => $request->input('gender'),
                'phone_code' => $request->input('phone_code'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'nationality' => $request->input('nationality'),
            ]);

            $settings->setCountry($request->input('country'));
            $settings->storeUser();
            $settings->storeSession();

        } catch (KatnissException $exception) {
            return $errorRdr->withErrors([$exception->getMessage()]);
        }

        return $settings->storeCookie(redirect(homeUrl('profile/user-information')))
            ->with('successes', [trans('error.success')]);
    }
}