<?php

namespace Katniss\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Katniss\Models\Helpers\AppConfig;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = Auth::guard($guard);
        if ($auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                session([AppConfig::KEY_REDIRECT_URL => $request->fullUrl()]);
                return redirect()->guest('auth/login');
            }
        }

        if (!$auth->user()->active) {
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
