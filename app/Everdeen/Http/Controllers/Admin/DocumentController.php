<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Utils\DateTimeHelper;

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

        return $this->_index([
            'dateFormat' => DateTimeHelper::shortDateFormat(),
            'timeFormat' => DateTimeHelper::shortTimeFormat(),
        ]);
    }
}
