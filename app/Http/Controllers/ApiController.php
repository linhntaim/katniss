<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-18
 * Time: 23:11
 */

namespace Katniss\Http\Controllers;


use Illuminate\Http\Request;

class ApiController extends KatnissController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    protected function response($failed, $data = null, $message = '')
    {
        return response()->json([
            '_success' => !$failed,
            '_messages' => empty($message) ? null : (array)$message,
            '_data' => $data,
        ]);
    }

    protected function responseSuccess($data = null)
    {
        return $this->response(false, $data);
    }

    protected function responseFail($message = '', $data = null)
    {
        return $this->response(true, $data, $message);
    }
}