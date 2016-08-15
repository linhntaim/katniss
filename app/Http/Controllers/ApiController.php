<?php

namespace Katniss\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends KatnissController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
}
