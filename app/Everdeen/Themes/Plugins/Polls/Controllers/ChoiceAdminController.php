<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-09
 * Time: 23:43
 */

namespace Katniss\Everdeen\Themes\Plugins\Polls\Controllers;

use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\Admin\AdminController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\Polls\Repositories\ChoiceRepository;
use Katniss\Everdeen\Themes\Plugins\Polls\Repositories\PollRepository;

class ChoiceAdminController extends AdminController
{
    protected $choiceRepository;

    public function __construct()
    {
        parent::__construct();

        $this->choiceRepository = new ChoiceRepository();
    }

    public function index(Request $request)
    {
        $choices = $this->choiceRepository->getPaged();

        return $request->theme()->resolveExtraView(
            'plugins.polls.admin.choice.index',
            trans('polls.page_poll_choices_title'),
            trans('polls.page_poll_choices_desc'),
            [
                'choices' => $choices,
                'pagination' => $this->paginationRender->renderByPagedModels($choices),
                'start_order' => $this->paginationRender->getRenderedPagination()['start_order'],
            ]
        );
    }

    public function create(Request $request)
    {
        $pollRepository = new PollRepository();

        return $request->theme()->resolveExtraView(
            'plugins.polls.admin.choice.create',
            trans('polls.page_poll_choices_title'),
            trans('polls.page_poll_choices_desc'),
            [
                'polls' => $pollRepository->getAll(),
            ]
        );
    }

    public function store(Request $request)
    {
        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        $this->_rdrUrl($request, adminUrl(), $rdrUrl, $errorRdrUrl);

        if ($validateResult->isFailed()) {
            return redirect($rdrUrl)
                ->withInput()
                ->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'poll' => 'required|exists:polls,id',
            'votes' => 'sometimes|integer',
        ]);
        if ($validator->fails()) {
            return redirect($rdrUrl)
                ->withInput()
                ->withErrors($validator);
        }

        try {
            $this->choiceRepository->create(
                $request->input('poll'),
                $request->input('votes', 0),
                $validateResult->getLocalizedInputs()
            );
        } catch (KatnissException $ex) {
            return redirect($rdrUrl)
                ->withInput()
                ->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function edit(Request $request, $id)
    {
        $choice = $this->choiceRepository->model($id);
        $pollRepository = new PollRepository();

        $this->_title([trans('pages.admin_links_title'), trans('form.action_edit')]);
        $this->_description(trans('pages.admin_links_desc'));

        return $request->theme()->resolveExtraView(
            'plugins.polls.admin.choice.edit',
            trans('polls.page_poll_choices_title'),
            trans('polls.page_poll_choices_desc'),
            [
                'choice' => $choice,
                'choice_poll' => $choice->poll,
                'polls' => $pollRepository->getAll(),
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $this->choiceRepository->model($id);

        $validateResult = $this->validateMultipleLocaleInputs($request, [
            'name' => 'required|max:255',
        ]);

        $this->_rdrUrl($request, adminUrl(), $rdrUrl, $errorRdrUrl);

        if ($validateResult->isFailed()) {
            return redirect($rdrUrl)->withErrors($validateResult->getFailed());
        }

        $validator = Validator::make($request->all(), [
            'poll' => 'required|exists:polls,id',
            'votes' => 'sometimes|integer',
        ]);
        if ($validator->fails()) {
            return redirect($rdrUrl)->withErrors($validator);
        }

        try {
            $this->choiceRepository->update(
                $request->input('poll'),
                $request->input('votes', 0),
                $validateResult->getLocalizedInputs()
            );
        } catch (KatnissException $ex) {
            return redirect($rdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }

    public function destroy(Request $request, $id)
    {
        $this->choiceRepository->model($id);

        $this->_rdrUrl($request, null, $rdrUrl, $errorRdrUrl);

        try {
            $this->choiceRepository->delete();
        } catch (KatnissException $ex) {
            return redirect($errorRdrUrl)->withErrors([$ex->getMessage()]);
        }

        return redirect($rdrUrl);
    }
}