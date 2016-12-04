<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 22:36
 */

namespace Katniss\Everdeen\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use Katniss\Everdeen\Http\Controllers\WebApiController;

class UserController extends WebApiController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getCsrfToken()
    {
        return $this->responseSuccess([
            'csrf_token' => csrf_token()
        ]);
    }

    public function getQuickLogin(Request $request)
    {
        if (!$this->validate($request, [
            'id' => 'required',
            'password' => 'required',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }


        if (!auth()->attempt($request->only('id', 'password'))) {
            return $this->responseFail();
        }

        return $this->responseSuccess([
            'csrf_token' => csrf_token(),
            'user' => auth()->user()
        ]);
    }
}