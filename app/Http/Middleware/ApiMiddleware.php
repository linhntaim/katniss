<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 05:02
 */

namespace Katniss\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\SettingsFacade;

class ApiMiddleware
{
    protected function checkSettings(Request $request)
    {
        if (!SettingsFacade::fromUser()) {
            if (!SettingsFacade::fromSession($request->session())) {
                SettingsFacade::fromCookie($request);
            }
        }
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
        $this->checkSettings($request);

        $forceLocale = SettingsFacade::getLocale();
        if ($request->has(AppConfig::KEY_FORCE_LOCALE)) {
            $forceLocale = $request->input(AppConfig::KEY_FORCE_LOCALE);
        }

        setCurrentLocale($forceLocale);

        return $next($request);
    }
}