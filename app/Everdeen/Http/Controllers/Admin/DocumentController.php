<?php

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\DateTimeHelper;

class DocumentController extends ViewController
{
    public function __construct()
    {
        parent::__construct();

        $this->viewPath = 'my_documents';
    }

    public function index(Request $request)
    {
        $this->_title(trans('pages.my_documents_title'));
        $this->_description(trans('pages.my_documents_desc'));

        return $this->_index([
            'dateFormat' => DateTimeHelper::shortDateFormat(),
            'timeFormat' => DateTimeHelper::shortTimeFormat(),
        ]);
    }
}
