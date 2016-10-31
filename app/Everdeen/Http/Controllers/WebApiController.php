<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 20:19
 */

namespace Katniss\Everdeen\Http\Controllers;


use Illuminate\Http\Request;

class WebApiController extends KatnissController
{
    use ApiResponseTrait;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
}