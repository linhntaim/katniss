<?php

namespace Katniss\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            $redirect_url = null;
            $user = $this->auth->user();
            $activatePath = homePath('auth/activate');
            $inactivePath = homePath('auth/inactive');
            if (!$user->active) {
                if (!$request->is($activatePath . '/*') && !$request->is($inactivePath)) {
                    $redirect_url = $inactivePath;
                }
            } else {
                $redirect_url = redirectUrlAfterLogin($user);
            }

            if (!empty($redirect_url)) {
                return redirect($redirect_url);
            }
        }

        return $next($request);
    }
}
