<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Models\Category;
use Katniss\Everdeen\Repositories\LinkCategoryRepository;

class LinkCategoryController extends ApiController
{
    protected $linkCategoryRepository;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->linkCategoryRepository = new LinkCategoryRepository($request->input('id'));
    }

    public function updateOrder(Request $request, $id)
    {
        $this->linkCategoryRepository->model($id);

        if (!$this->validate($request, [
            'link_ids' => 'required|array|exists:links,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        try {
            $this->linkCategoryRepository->updateSort($request->input('link_ids'));
        } catch (KatnissException $ex) {
            return $this->responseFail($ex->getMessage());
        }

        return $this->responseSuccess();
    }
}
