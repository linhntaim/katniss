<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Repositories\UserRepository;

class AccountController extends ViewController
{
    protected $userRepository;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'my_account';
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        $this->theme->title(trans('pages.my_account_title'));
        $this->theme->description(trans('pages.my_account_desc'));

        return $this->_view();
    }

    public function update(Request $request)
    {
        $this->userRepository->model($this->authUser);

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|password',
            'display_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->authUser->id . ',id',
            'name' => 'required|max:255|unique:users,name,' . $this->authUser->id . ',id',
            'password' => 'sometimes|confirmed|min:6',
        ]);

        $redirect = redirect(meUrl('account'));

        if ($validator->fails()) {
            return $redirect->withErrors($validator);
        }

        try {
            $this->userRepository->update(
                $request->input('name'),
                $request->input('display_name'),
                $request->input('email'),
                $request->input('password', ''),
                null,
                $this->globalViewParams
            );
        } catch (KatnissException $ex) {
            return $redirect->withErrors([$ex->getMessage()]);
        }

        return $redirect->with('successes', [trans('error.success')]);
    }
}
