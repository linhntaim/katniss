<?php

namespace Katniss\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Katniss\Http\Controllers\ViewController;

class AccountController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'my_account';
    }

    public function index()
    {
        $this->theme->title(trans('pages.my_account_title'));
        $this->theme->description(trans('pages.my_account_desc'));

        return $this->_view();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|password',
            'display_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->authUser->id . ',id',
            'name' => 'required|max:255|unique:users,name,' . $this->authUser->id . ',id',
            'password' => 'sometimes|confirmed|min:6',
        ]);

        $redirect = redirect(homeUrl('me/account'));

        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        $this->authUser->display_name = $request->input('display_name');
        $this->authUser->email = $request->input('email');
        $this->authUser->name = $request->input('name');
        if ($request->has('password')) {
            $this->authUser->password = bcrypt($request->input('password'));
        }
        $this->authUser->save();

        return $redirect->with('successes', [trans('error.success')]);
    }
}
