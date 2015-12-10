<?php

namespace Katniss\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Katniss\Events\UserPasswordChanged;
use Katniss\Http\Controllers\ViewController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordController extends ViewController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $subject;
    protected $redirectPath;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->subject = '[' . appName() . '] ' . trans('pages.account_password_reset_title');
        $this->redirectPath = homePath('auth/inactive');

        $this->middleware('guest');
    }

    public function getEmail()
    {
        $this->theme->title(trans('pages.account_password_reset_title'));
        $this->theme->description(trans('pages.account_password_reset_desc'));

        return view($this->themePage('auth.password'));
    }

    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        $this->theme->title(trans('pages.account_password_reset_title'));
        $this->theme->description(trans('pages.account_password_reset_desc'));

        return view($this->themePage('auth.reset'))->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->save();

        event(new UserPasswordChanged($user, $password));

        Auth::login($user);
    }
}
