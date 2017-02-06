<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-08
 * Time: 19:16
 */

namespace Katniss\Everdeen\Http\Controllers\Admin;

use Katniss\Everdeen\Http\Controllers\ViewController;

class AdminController extends ViewController
{
    public function __construct()
    {
        parent::__construct();

        $this->paginationRender->reset();
        $this->paginationRender->setDefault('wrapClass', 'pagination pagination-sm no-margin pull-right');
    }
}