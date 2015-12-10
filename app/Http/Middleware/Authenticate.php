<?php

namespace Katniss\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Katniss\Models\Helpers\AppConfig;

class Authenticate
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
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                session([AppConfig::KEY_REDIRECT_URL => $request->fullUrl()]);
                return redirect()->guest('auth/login');
            }
        }

        if (!$this->auth->user()->active) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

            $activatePath = homePath('auth/activate');
            $inactivePath = homePath('auth/inactive');
            if (!$request->is($activatePath . '/*') && !$request->is($inactivePath)) {
                return redirect($inactivePath);
            }
        }

        return $next($request);
    }
}
