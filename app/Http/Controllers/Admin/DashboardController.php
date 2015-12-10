<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Http\Controllers\ViewController;
use Katniss\Models\BlogArticle;
use Katniss\Models\Teacher;
use Katniss\Models\TmpLearningRequest;
use Katniss\Models\Topic;
use Illuminate\Http\Request;

use Katniss\Http\Requests;

class DashboardController extends ViewController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view($this->themePage('dashboard'), [
        ]);
    }
}
