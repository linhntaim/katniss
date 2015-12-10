<?php

namespace Katniss\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Katniss\Http\Requests;
use Katniss\Http\Controllers\Controller;
use Katniss\Models\Category;

class LinkCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateOrder(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'link_ids' => 'required|array|exists:links,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => $validator->errors()->all()
            ]);
        }

        $link_ids = $request->input('link_ids');
        $order = 0;
        $category_links = $category->links();
        foreach ($link_ids as $link_id) {
            ++$order;
            $category_links->updateExistingPivot($link_id, ['order' => $order]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
