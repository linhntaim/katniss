<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 01:04
 */

namespace Katniss\Everdeen\Http\Middleware;

use Closure;
use Katniss\Everdeen\Http\Request;

class CorsOnErrorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($response->status() != 200) {
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Headers', 'X-Requested-With, X-Socket-Id, Content-Type, Accept, Origin, Authorization');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        }
        return $response;
    }
}