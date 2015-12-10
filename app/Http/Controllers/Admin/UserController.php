<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Events\UserAfterRegistered;
use Katniss\Events\UserPasswordChanged;
use Katniss\Http\Controllers\ViewController;
use Katniss\Models\Helpers\DateTimeHelper;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\MailHelper;
use Katniss\Models\Helpers\PaginationHelper;
use Katniss\Models\Helpers\QueryStringBuilder;
use Katniss\Models\Role;
use Katniss\Models\User;
use Illuminate\Http\Request;

use Katniss\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class UserController extends ViewController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE); // Helper::DEFAULT_ITEMS_PER_PAGE items per page
        $users_query = new QueryStringBuilder([
            'page' => $users->currentPage()
        ], adminUrl('users'));
        return view($this->themePage('user.list'), [
            'users' => $users,
            'users_query' => $users_query,
            'page_helper' => new PaginationHelper($users->lastPage(), $users->currentPage(), $users->perPage()),
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view($this->themePage('user.add'), [
            'roles' => Role::haveStatuses([Role::STATUS_NORMAL])->get(),
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    protected function validator(array $data, array $extra_rules = [])
    {
        return Validator::make($data, array_merge([
            'display_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'name' => 'required|max:255',
            'password' => 'required|min:6',
            'roles' => 'required|array|exists:roles,id,status,' . Role::STATUS_NORMAL,
        ], $extra_rules));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        $error_rdr = redirect(adminUrl('users/add'))->withInput();

        if ($validator->fails()) {
            return $error_rdr->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $user = User::create(array(
                'display_name' => $request->input('display_name'),
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => bcrypt($request->input('password')),
                'activation_code' => str_random(32),
                'active' => false
            ));
            $user->save();
            $user->attachRoles($request->input('roles'));

            if ($request->has('send_welcomed_mail')) {
                event(new UserAfterRegistered($user, array_merge($this->globalViewParams, [
                    MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                    MailHelper::EMAIL_TO => $user->email,
                    MailHelper::EMAIL_TO_NAME => $user->display_name,

                    'password' => $request->input('password'),
                ])));
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return $error_rdr->withErrors([trans('error.database_insert') . ' (' . $ex->getMessage() . ')']);
        }

        return redirect(adminUrl('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);

        return view($this->themePage('user.edit'), [
            'user' => $user,
            'user_roles' => $user->roles,
            'roles' => Role::haveStatuses([Role::STATUS_NORMAL])->get(),
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->input('id'));
        $validator = $this->validator($request->all(), [
            'password' => 'sometimes|min:6',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $rdr = redirect(adminUrl('users/{id}/edit', ['id' => $user->id]));

        if ($validator->fails()) {
            return $rdr->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $passwordChanged = false;
            $user->display_name = $request->input('display_name');
            $user->email = $request->input('email');
            if (!empty($request->input('password', ''))) {
                $user->password = bcrypt($request->input('password'));
                $passwordChanged = true;
            }
            $user->name = $request->input('name');
            $user->save();

            $user->roles()->sync($request->input('roles'));

            if ($passwordChanged) {
                event(new UserPasswordChanged($user, $request->input('password'),
                    array_merge($this->globalViewParams, [
                        MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                        MailHelper::EMAIL_TO => $user->email,
                        MailHelper::EMAIL_TO_NAME => $user->display_name,
                    ])
                ));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $rdr->withErrors([trans('error.database_update') . ' (' . $e->getMessage() . ')']);
        }

        return $rdr;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $redirect_url = adminUrl('users');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        if ($user->hasRole('owner')) {
            return redirect($redirect_url)->withErrors([trans('error._cannot_delete', ['reason' => trans('error.is_role_owner')])]);
        }

        return $user->delete() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }
}
