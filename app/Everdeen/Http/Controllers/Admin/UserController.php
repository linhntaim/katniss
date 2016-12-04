<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Repositories\RoleRepository;
use Katniss\Everdeen\Repositories\UserRepository;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\PaginationHelper;
use Katniss\Everdeen\Utils\QueryStringBuilder;
use Katniss\Everdeen\Models\Role;


class UserController extends ViewController
{
    protected $userRepository;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'user';
        $this->userRepository = new UserRepository($request->input('id'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->theme->title(trans('pages.admin_users_title'));
        $this->theme->description(trans('pages.admin_users_desc'));

        $users = $this->userRepository->getPaged();
        $users_query = new QueryStringBuilder([
            'page' => $users->currentPage()
        ], adminUrl('users'));
        return $this->_list([
            'users' => $users,
            'users_query' => $users_query,
            'page_helper' => new PaginationHelper($users->lastPage(), $users->currentPage(), $users->perPage()),
            'rdr_param' => rdrQueryParam($request->fullUrl()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $roleRepository = new RoleRepository();

        $this->theme->title([trans('pages.admin_users_title'), trans('form.action_add')]);
        $this->theme->description(trans('pages.admin_users_desc'));

        return $this->_add([
            'roles' => $roleRepository->getByHavingStatuses([Role::STATUS_NORMAL]),
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
            'roles' => 'sometimes|array|exists:roles,id,status,' . Role::STATUS_NORMAL,
        ], $extra_rules));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        $errorRdr = redirect(adminUrl('users/add'))->withInput();

        if ($validator->fails()) {
            return $errorRdr->withErrors($validator);
        }

        try {
            $this->userRepository->create(
                $request->input('name'),
                $request->input('display_name'),
                $request->input('email'),
                $request->input('password'),
                $request->input('roles'),
                $request->has('send_welcomed_mail'),
                $this->globalViewParams
            );
        } catch (KatnissException $ex) {
            return $errorRdr->withErrors([$ex->getMessage()]);
        }

        return redirect(adminUrl('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $user = $this->userRepository->model($id);
        $roleRepository = new RoleRepository();

        $this->theme->title([trans('pages.admin_users_title'), trans('form.action_edit')]);
        $this->theme->description(trans('pages.admin_users_desc'));

        return $this->_edit([
            'user' => $user,
            'user_roles' => $user->roles,
            'owner_role' => $roleRepository->getByName('owner'),
            'roles' => $roleRepository->getByHavingStatuses([Role::STATUS_NORMAL]),
            'date_js_format' => DateTimeHelper::shortDatePickerJsFormat(),
            'rdr_param' => errorRdrQueryParam($request->fullUrl()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = $this->userRepository->model();

        $rdr = redirect(adminUrl('users/{id}/edit', ['id' => $user->id]));

        $validator = $this->validator($request->all(), [
            'password' => 'sometimes|min:6',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return $rdr->withErrors($validator);
        }

        try {
            $this->userRepository->update(
                $request->input('name'),
                $request->input('display_name'),
                $request->input('email'),
                $request->input('password', ''),
                $request->input('roles'),
                $this->globalViewParams
            );
        } catch (KatnissException $ex) {
            return $rdr->withErrors([$ex->getMessage()]);
        }

        return $rdr;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $this->userRepository->model($id);

        $this->_rdrUrl($request, adminUrl('users'), $rdrUrl, $errorRdrUrl);

        try {
            $this->userRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
