<?php

namespace Katniss\Everdeen\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Katniss\Everdeen\Http\Controllers\ApiController;
use Katniss\Everdeen\Models\Category;

class LinkCategoryController extends ApiController
{
    public function updateOrder(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if (!$this->validate($request, [
            'link_ids' => 'required|array|exists:links,id',
        ])
        ) {
            return $this->responseFail($this->getValidationErrors());
        }

        $link_ids = $request->input('link_ids');
        $order = 0;
        $category_links = $category->links();
        foreach ($link_ids as $link_id) {
            ++$order;
            $category_links->updateExistingPivot($link_id, ['order' => $order]);
        }

        return $this->responseSuccess();
    }
}
