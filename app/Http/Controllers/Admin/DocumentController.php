<?php

namespace Katniss\Http\Controllers\Admin;

use Katniss\Http\Controllers\ViewController;
use Katniss\Models\Helpers\DateTimeHelper;
use Illuminate\Http\Request;

use Katniss\Http\Requests;
use Illuminate\Support\Facades\Storage;

class DocumentController extends ViewController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->viewPath = 'my_documents';
    }

    public function index(Request $request)
    {
        $this->theme->title(trans('pages.my_documents_title'));
        $this->theme->description(trans('pages.my_documents_desc'));

        return $this->_list([
            'dateFormat' => DateTimeHelper::shortDateFormat(),
            'timeFormat' => DateTimeHelper::shortTimeFormat(),
        ]);
    }
}
