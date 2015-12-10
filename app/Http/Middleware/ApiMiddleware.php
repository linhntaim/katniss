<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 05:02
 */

namespace Katniss\Http\Middleware;

use Closure;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('settings')) {
            session([
                'settings.locale' => currentLocaleCode(),
                'settings.country' => 'US',
                'settings.timezone' => 'UTC',
                'settings.first_day_of_week' => 0,
                'settings.long_date_format' => 0,
                'settings.short_date_format' => 0,
                'settings.long_time_format' => 0,
                'settings.short_time_format' => 0,
            ]);
        } else {
            setCurrentLocale($request->session()->get('settings.locale'));
        }
        return $next($request);
    }
}