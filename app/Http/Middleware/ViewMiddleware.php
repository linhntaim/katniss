<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-11
 * Time: 03:20
 */

namespace Katniss\Http\Middleware;

use Closure;

class ViewMiddleware
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
            session([
                'settings.locale' => currentLocaleCode()
            ]);
        }
        return $next($request);
    }
}