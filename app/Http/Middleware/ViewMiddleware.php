<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-11
 * Time: 03:20
 */

namespace Katniss\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\Settings;
use Katniss\Models\Helpers\SettingsFacade;

class ViewMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var bool
     */
    protected $continueCookie;

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

    protected function checkSettings(Request $request)
    {
        $continueSession = SettingsFacade::fromSession($request->session());
        $needCheckCookie = false;
        if (SettingsFacade::fromUser()) {
            SettingsFacade::storeSession();
            $needCheckCookie = true;
        } else {
            if (!$continueSession) {
                if (!SettingsFacade::fromCookie($request)) { // no cookie, no session
                    SettingsFacade::makeAllChanges();
                }
                SettingsFacade::storeSession();
            } else {
                $needCheckCookie = true;
            }
        }
        if ($needCheckCookie) {
            $cookieSettings = new Settings();
            if (!$cookieSettings->fromCookie($request)) { // no cookie, has session
                SettingsFacade::makeAllChanges();
            } else {
                SettingsFacade::makeOnlyChanges(SettingsFacade::compare($cookieSettings));
            }
        }
    }

    protected function checkForceLocale(Request $request)
    {
        $allSupportedLocaleCodes = allSupportedLocaleCodes();
        $isDirectLocale = in_array($request->segment(1), $allSupportedLocaleCodes);
        $forceLocale = $isDirectLocale ? currentLocaleCode() : SettingsFacade::getLocale();
        if ($request->has(AppConfig::KEY_FORCE_LANG)) {
            $forceLocale = $request->input(AppConfig::KEY_FORCE_LANG);
        }
        if (in_array($forceLocale, $allSupportedLocaleCodes)) {
            if ($forceLocale != SettingsFacade::getLocale()) {
                SettingsFacade::setLocale($forceLocale);
                SettingsFacade::storeSession();
                SettingsFacade::storeUser();
            }
            if ($forceLocale != currentLocaleCode()) {
                $rdr = redirect(currentURL($forceLocale));
                return SettingsFacade::storeCookie($rdr);
            }
        }

        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->checkSettings($request);

        if ($request->has(AppConfig::KEY_REDIRECT_URL)) {
            session([AppConfig::KEY_REDIRECT_URL => $request->input(AppConfig::KEY_REDIRECT_URL)]);
        }

        $localeRedirect = $this->checkForceLocale($request);
        if ($localeRedirect !== false) {
            return $localeRedirect;
        }

        return SettingsFacade::storeCookie($next($request));
    }
}