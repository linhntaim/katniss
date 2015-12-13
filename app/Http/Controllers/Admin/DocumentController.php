<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Http\Controllers\ViewController;
use Katniss\Models\Helpers\DateTimeHelper;
use Illuminate\Http\Request;

use Katniss\Http\Requests;
use Illuminate\Support\Facades\Storage;

class DocumentController extends ViewController
{
    public function index(Request $request)
    {
        return view($this->themePage('my_documents.index'), [
            'dateFormat' => DateTimeHelper::shortDateFormat(),
            'timeFormat' => DateTimeHelper::shortTimeFormat(),
        ]);
    }
}
