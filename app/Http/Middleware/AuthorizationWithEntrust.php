<?php

namespace Katniss\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AuthorizationWithEntrust
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param null $roles
     * @param null $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null, $permissions = null)
    {
        $roles = empty($roles) ? null : explode('|', $roles);
        $permissions = empty($permissions) ? null : explode('|', $permissions);

        if (!$this->auth->user()->ability($roles, $permissions)) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
