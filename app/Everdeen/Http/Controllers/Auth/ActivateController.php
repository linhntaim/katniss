<?php

namespace Katniss\Everdeen\Http\Controllers\Auth;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Utils\MailHelper;

class ActivateController extends ViewController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'auth';
    }

    public function getInactive()
    {
        if ($this->authUser->active) {
            return redirect(redirectUrlAfterLogin($this->authUser));
        }

        $this->_title(trans('pages.account_inactive_title'));
        $this->_description(trans('pages.account_inactive_desc'));

        return $this->_any('inactive', ['resend' => false]);
    }

    public function postInactive()
    {
        MailHelper::sendTemplate('welcome', array_merge([
            MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
            MailHelper::EMAIL_TO => $this->authUser->email,
            MailHelper::EMAIL_TO_NAME => $this->authUser->display_name,

            'id' => $this->authUser->id,
            'display_name' => $this->authUser->display_name,
            'name' => $this->authUser->name,
            'email' => $this->authUser->email,
            'password' => '******',
            'activation_code' => $this->authUser->activation_code,
            'url_activate' => homeUrl(
                'auth/activate/{id}/{activation_code}',
                [
                    'id' => $this->authUser->id,
                    'activation_code' => $this->authUser->activation_code
                ]
            ),
        ], $this->_params()));

        $this->_title(trans('pages.account_inactive_title'));
        $this->_description(trans('pages.account_inactive_title'));

        return $this->_any('inactive', ['resend' => true]);
    }

    public function getActivation($id, $activation_code)
    {
        // if user has logged in but has the id not equals $id, the activation will not process
        // due to the middleware 'guest' applied to this controller in the constructor

        $user = User::findOrFail($id);
        $active = $user->activation_code == $activation_code;
        if ($active) {
            $user->active = true;
            $user->save();
        }

        $this->_title(trans('pages.account_activate_title'));
        $this->_description(trans('pages.account_activate_title'));

        return $this->_any('activate', [
            'active' => $active,
            'url' => $this->isAuth ? redirectUrlAfterLogin($this->authUser) : homeUrl('auth/login'),
        ]);
    }
}
