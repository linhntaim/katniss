<?php

namespace Katniss\Http\Controllers\Home;

use Illuminate\Http\Request;

use Katniss\Http\Requests;
use Katniss\Http\Controllers\ViewController;
use Katniss\Models\Helpers\ExtraActions\CallableObject;
use Katniss\Models\Helpers\Menu;
use Katniss\Models\Helpers\MenuItem;

class HomepageController extends ViewController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        add_filter('main_menu', new CallableObject(function (Menu $menu) {
            $menu->addItem(new MenuItem(
                '#social-sharing',
                'Social Sharing', 'li', null, 'page-scroll'
            ));
            $menu->addItem(new MenuItem(
                '#facebook-comment',
                'Facebook Comment', 'li', null, 'page-scroll'
            ));
            return $menu;
        }));
        return view($this->themePage('home'));
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
}
