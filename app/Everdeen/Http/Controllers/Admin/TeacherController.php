<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\TeacherRepository;

class TeacherController extends AdminController
{
    protected $teacherRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'teacher';
        $this->teacherRepository = new TeacherRepository();
    }

    public function indexApproved(Request $request)
    {
        $searchDisplayName = $request->input('display_name', null);
        $searchEmail = $request->input('email', null);
        $searchSkypeId = $request->input('skype_id', null);
        $searchPhoneNumber = $request->input('phone_number', null);
        $teachers = $this->teacherRepository->getSearchApprovedPaged(
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_approved_teachers_title'));
        $this->_description(trans('pages.admin_approved_teachers_desc'));

        return $this->_any('index_approved', [
            'teachers' => $teachers,
            'pagination' => $this->paginationRender->renderByPagedModels($teachers),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'search_display_name' => $searchDisplayName,
            'search_email' => $searchEmail,
            'search_skype_id' => $searchSkypeId,
            'search_phone_number' => $searchPhoneNumber,
        ]);
    }

    public function indexRegistering(Request $request)
    {
        $searchDisplayName = $request->input('display_name', null);
        $searchEmail = $request->input('email', null);
        $searchSkypeId = $request->input('skype_id', null);
        $searchPhoneNumber = $request->input('phone_number', null);
        $teachers = $this->teacherRepository->getSearchRegisteringPaged(
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_registering_teachers_title'));
        $this->_description(trans('pages.admin_registering_teachers_desc'));

        return $this->_any('index_registering', [
            'teachers' => $teachers,
            'pagination' => $this->paginationRender->renderByPagedModels($teachers),
            'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],

            'search_display_name' => $searchDisplayName,
            'search_email' => $searchEmail,
            'search_skype_id' => $searchSkypeId,
            'search_phone_number' => $searchPhoneNumber,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->has('reject')) {
            return $this->reject($request, $id);
        }
        if ($request->has('approve')) {
            return $this->approve($request, $id);
        }

        return '';
    }

    protected function reject(Request $request, $id)
    {
        $this->teacherRepository->model($id);

        $this->_rdrUrl($request, adminUrl('approved-teachers'), $rdrUrl, $errorRdrUrl);

        try {
            $this->teacherRepository->reject();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function approve(Request $request, $id)
    {
        $this->teacherRepository->model($id);

        $this->_rdrUrl($request, adminUrl('registering-teachers'), $rdrUrl, $errorRdrUrl);

        try {
            $this->teacherRepository->approve();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
