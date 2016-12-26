<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 20:19
 */

namespace Katniss\Everdeen\Http\Controllers;

class WebApiController extends KatnissController
{
    use ApiResponseTrait;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('theme');
    }
}