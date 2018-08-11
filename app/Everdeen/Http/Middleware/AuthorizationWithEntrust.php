<?php

namespace Katniss\Everdeen\Http\Middleware;

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
     * @param \Katniss\Everdeen\Http\Request $request
     * @param \Closure $next
     * @param null $roles
     * @param null $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null, $permissions = null)
    {
        $preRoles = empty($roles) ? null : explode('|', $roles);
        $prePermissions = empty($permissions) ? null : explode('|', $permissions);
        $checkingRoles = empty($preRoles) ? null : [];
        $checkingOnlyRoles = $checkingRoles;
        $checkingPermissions = empty($prePermissions) ? null : [];
        $checkingOnlyPermissions = $checkingPermissions;
        if(!empty($preRoles)) {
            foreach ($preRoles as $preRole) {
                $parts = explode('!', $preRole);
                if (count($parts) == 1) {
                    $checkingRoles[] = $parts[0];
                } else {
                    $checkingOnlyRoles[$parts[0]] = $parts[1];
                }
            }
        }
        if(!empty($prePermissions)) {
            foreach ($prePermissions as $prePermission) {
                $parts = explode('!', $prePermission);
                if (count($parts) == 1) {
                    $checkingPermissions[] = $parts[0];
                } else {
                    $checkingOnlyPermissions[$parts[0]] = $parts[1];
                }
            }
        }

        $user = $this->auth->user();

        if ($user->ability($checkingRoles, $checkingPermissions)) {
            return $next($request);
        }

        if(!empty($checkingOnlyRoles)) {
            foreach ($checkingOnlyRoles as $role => $inputName) {
                if ($user->hasRole($role) && $request->has($inputName)) {
                    return $next($request);
                }
            }
        }

        if(!empty($checkingOnlyPermissions)) {
            foreach ($checkingOnlyPermissions as $permission => $inputName) {
                if ($user->can($permission) && $request->has($inputName)) {
                    return $next($request);
                }
            }
        }

        return abort(403);
    }
}
