<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Events\UserAfterRegistered;
use Katniss\Http\Controllers\ViewController;
use Katniss\Models\Helpers\DateTimeHelper;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\MailHelper;
use Katniss\Models\Helpers\PaginationHelper;
use Katniss\Models\Helpers\QueryStringBuilder;
use Katniss\Models\UserRole;
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
            'roles' => UserRole::all(),
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
            'roles' => 'required|array|exists:user_roles,id',
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

        $rdr = redirect(adminUrl('users/add'));

        if ($validator->fails()) {
            return $rdr->withInput()->withErrors($validator);
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

            event(new UserAfterRegistered($user, array_merge($this->globalViewParams, [
                MailHelper::EMAIL_SUBJECT => trans('label.welcome_to_') . appName(),
                MailHelper::EMAIL_TO => $user->email,
                MailHelper::EMAIL_TO_NAME => $user->display_name,

                'password' => $request->input('password'),
            ])));

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return $rdr->withInput()->withErrors([trans('error.database_insert') . ' (' . $ex->getMessage() . ')']);
        }

        return $rdr;
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
            'roles' => UserRole::where('public', false)->get(),
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
        if ($validator->fails()) {
            return redirect(adminUrl('users/{id}/edit', ['id' => $user->id]))
                ->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $user->name = $request->input('name');
            $slug = toSlug($request->input('name'));
            if (User::where('slug', $slug)->where('id', '<>', $user->id)->count() > 0) {
                $user->slug = $slug . '-' . $user->id;
            } else {
                $user->slug = $slug;
            }
            $user->email = $request->input('email');
            if (!empty($request->input('password', ''))) {
                $user->password = bcrypt($request->input('password'));
            }
            $user->phone_code = $request->input('phone_code');
            $user->phone = $request->input('phone');
            $user->skype = $request->input('skype');
            $user->date_of_birth = toDatabaseTime(DateTimeHelper::shortDateFormat(), $request->input('date_of_birth'), true);
            $user->gender = $request->input('gender');
            $user->address = $request->input('address', '');
            $user->city = $request->input('city', '');
            $user->country = $request->input('country');
            $user->language = $request->input('language');
            $user->save();

            $selected_roles = $request->input('roles', []);
            if (count($selected_roles) > 0) {
                $user->roles()->sync($selected_roles);
            } else {
                $user->roles()->detach();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(adminUrl('users/{id}/edit', ['id' => $user->id]))
                ->withInput()
                ->withErrors([trans('error.database_update') . ' (' . $e->getMessage() . ')']);
        }

        return redirect(adminUrl('users/{id}/edit', ['id' => $user->id]));
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

        return $user->delete() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_delete')]);
    }

    public function listVerifyingCertificates(Request $request)
    {
        $certificates = UserRecord::ofCertificate()->requested()->paginate(AppConfig::DEFAULT_ITEMS_PER_PAGE);
        $query = new QueryStringBuilder([
            'page' => $certificates->currentPage()
        ], adminUrl('users/verifying-certificates'));
        return view($this->themePage('user.verifying_certificates'), [
            'certificates' => $certificates,
            'query' => $query,
            'page_helper' => new PaginationHelper($certificates->lastPage(), $certificates->currentPage(), $certificates->perPage()),
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    public function verifyCertificate(Request $request, $id)
    {
        $record = UserRecord::ofCertificate()->where('id', $id)->firstOrFail();
        $record->status = UserRecord::STATUS_VERIFIED;

        $redirect_url = adminUrl('users/verifying-certificates');
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $redirect_url = $rdr;
        }

        return $record->save() === true ? redirect($redirect_url) : redirect($redirect_url)->withErrors([trans('error.database_update')]);
    }
}
