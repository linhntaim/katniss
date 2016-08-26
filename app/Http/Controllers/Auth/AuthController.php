<?php

namespace Katniss\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Katniss\Events\UserAfterRegistered;
use Katniss\Models\Helpers\MailHelper;
use Katniss\Models\User;
use Katniss\Http\Controllers\ViewController;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Katniss\Models\Role;
use Katniss\Models\UserSocial;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends ViewController
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $redirectPath;
    protected $loginPath;
    protected $redirectAfterLogout;
    protected $socialRegisterPath;
    protected $username;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->redirectPath = homePath('auth/inactive');
        $this->loginPath = homePath('auth/login');
        $this->redirectAfterLogout = homePath();
        $this->socialRegisterPath = homePath('auth/register/social');

        $this->username = 'account';

        $this->middleware('guest', ['except' => ['getLogout']]);
        $this->middleware('auth', ['only' => ['getInactive', 'postInactive']]);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $this->theme->title(trans('pages.account_login_title'));
        $this->theme->description(trans('pages.account_login_desc'));

        return view($this->themePage('auth.login'));
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->loginUsername() => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->loginPath)
                ->withInput($request->only($this->loginUsername(), 'remember'))
                ->withErrors($validator);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        // try email
        $try_credentials = [
            'email' => $credentials[$this->loginUsername()],
            'password' => $credentials['password'],
        ];
        if (Auth::attempt($try_credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }
        // try user name
        $try_credentials = [
            'name' => $credentials[$this->loginUsername()],
            'password' => $credentials['password'],
        ];
        if (Auth::attempt($try_credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    protected function authenticated(Request $request, User $user)
    {
        return redirect(homeUrl('auth/inactive', [], $user->settings->locale));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @param boolean $fromSocial
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $fromSocial = false)
    {
        $rules = [
            'display_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'name' => 'required|max:255|unique:users,name',
            'password' => 'required|confirmed|min:6',
        ];
        if ($fromSocial) {
            $rules['provider'] = 'required';
            $rules['provider_id'] = 'required';
            $rules['url_avatar'] = 'required|url';
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @param boolean $fromSocial
     * @return User
     */
    protected function create(array $data, $fromSocial = false)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'display_name' => $data['display_name'],
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'url_avatar' => $data['url_avatar'],
                'url_avatar_thumb' => $data['url_avatar'],
                'activation_code' => str_random(32),
            ]);
            $defaultRole = Role::where('name', 'user')->firstOrFail();
            $user->attachRole($defaultRole->id);

            if ($fromSocial) {
                $user->socialProviders()->save(new UserSocial([
                    'provider' => $data['provider'],
                    'provider_id' => $data['provider_id'],
                ]));
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return false;
        }

        return $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $this->theme->title(trans('pages.account_register_title'));
        $this->theme->description(trans('pages.account_register_desc'));

        return view($this->themePage('auth.register'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        $errorRdr = redirect(homeUrl('auth/register'))->withInput();
        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        $storedUser = $this->create($request->all());
        if ($storedUser) {
            event(new UserAfterRegistered($storedUser, $request->input('password'), false,
                array_merge($this->globalViewParams, [
                    MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                    MailHelper::EMAIL_TO => $storedUser->email,
                    MailHelper::EMAIL_TO_NAME => $storedUser->display_name,
                ])
            ));

            Auth::login($storedUser);
        } else {
            return $errorRdr->withErrors([trans('auth.register_failed_system_error')]);
        }

        return redirect($this->redirectPath());
    }

    public function redirectToProvider(Request $request, $provider)
    {
        if (!config('services.' . $provider)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
        if (!config('services.' . $provider)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $ex) {
            return redirect($this->loginPath)
                ->withErrors([trans('error.fail_social_get_info') . ' (' . $ex->getMessage() . ')']);
        }

        if ($authUser = User::fromSocial($provider, $socialUser->id, $socialUser->email)->first()) {
            Auth::login($authUser);
            return redirect($this->redirectPath);
        }

        $userName = strtok($socialUser->email, '@');
        $users = User::where('name', 'like', $userName . '%')->get();
        if ($users->where('name', $userName)->count() > 0) {
            $userName = $userName . '.' . $users->count();
        }

        return redirect(homeUrl('auth/register/social'))
            ->withInput([
                'provider' => $provider,
                'provider_id' => $socialUser->id,
                'url_avatar' => $socialUser->avatar,
                'display_name' => $socialUser->name,
                'email' => $socialUser->email,
                'name' => $userName,
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getSocialRegister(Request $request)
    {
        $this->theme->title(trans('pages.account_register_title'));
        $this->theme->description(trans('pages.account_register_desc'));

        return view($this->themePage('auth.register_social'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSocialRegister(Request $request)
    {
        $validator = $this->validator($request->all(), true);

        $error_rdr = redirect(homeUrl('auth/register/social'))->withInput();

        if ($validator->fails()) {
            return $error_rdr->withErrors($validator);
        }

        $stored_user = $this->create($request->all(), true);
        if ($stored_user) {
            event(new UserAfterRegistered($stored_user, $request->input('password'), true,
                array_merge($this->globalViewParams, [
                    MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                    MailHelper::EMAIL_TO => $stored_user->email,
                    MailHelper::EMAIL_TO_NAME => $stored_user->display_name,

                    'provider' => ucfirst($request->input('provider')),
                    'provider_id' => $request->input('provider_id'),
                ])
            ));

            Auth::login($stored_user);
        } else {
            return $error_rdr->withErrors([trans('auth.register_failed_system_error')]);
        }

        return redirect($this->redirectPath);
    }

    public function getInactive(Request $request)
    {
        if ($this->authUser->active) {
            return redirect(redirectUrlAfterLogin($this->authUser));
        }

        $this->theme->title(trans('pages.account_inactive_title'));
        $this->theme->description(trans('pages.account_inactive_desc'));

        return view($this->themePage('auth.inactive'), ['resend' => false]);
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
            'url_activate' => homeUrl('auth/activate/{id}/{activation_code}', ['id' => $this->authUser->id, 'activation_code' => $this->authUser->activation_code]),
        ], $this->globalViewParams));

        $this->theme->title(trans('pages.account_inactive_title'));
        $this->theme->description(trans('pages.account_inactive_title'));

        return view($this->themePage('auth.inactive'), ['resend' => true]);
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

        $this->theme->title(trans('pages.account_activate_title'));
        $this->theme->description(trans('pages.account_activate_title'));

        return view($this->themePage('auth.activate'), [
            'active' => $active,
            'url' => $this->isAuth ? redirectUrlAfterLogin($this->authUser) : homeUrl('auth/login'),
        ]);
    }
}
