<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Repositories\StudentRepository;

class StudentController extends AdminController
{
    protected $studentRepository;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'student';
        $this->studentRepository = new StudentRepository();
    }

    public function indexApproved(Request $request)
    {
        $searchDisplayName = $request->input('display_name', null);
        $searchEmail = $request->input('email', null);
        $searchSkypeId = $request->input('skype_id', null);
        $searchPhoneNumber = $request->input('phone_number', null);
        $students = $this->studentRepository->getSearchApprovedPaged(
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_approved_students_title'));
        $this->_description(trans('pages.admin_approved_students_desc'));

        return $this->_any('index_approved', [
            'students' => $students,
            'pagination' => $this->paginationRender->renderByPagedModels($students),
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
        $students = $this->studentRepository->getSearchRegisteringPaged(
            $searchDisplayName,
            $searchEmail,
            $searchSkypeId,
            $searchPhoneNumber
        );

        $this->_title(trans('pages.admin_registering_students_title'));
        $this->_description(trans('pages.admin_registering_students_desc'));

        return $this->_any('index_registering', [
            'students' => $students,
            'pagination' => $this->paginationRender->renderByPagedModels($students),
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
        $this->studentRepository->model($id);

        $this->_rdrUrl($request, adminUrl('approved-students'), $rdrUrl, $errorRdrUrl);

        try {
            $this->studentRepository->reject();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    protected function approve(Request $request, $id)
    {
        $this->studentRepository->model($id);

        $this->_rdrUrl($request, adminUrl('registering-students'), $rdrUrl, $errorRdrUrl);

        try {
            $this->studentRepository->approve();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}
