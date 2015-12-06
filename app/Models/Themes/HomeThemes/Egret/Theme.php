<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-07
 * Time: 02:53
 */

namespace Katniss\Models\Themes\HomeThemes\Egret;


use Katniss\Models\Themes\HomeThemes\HomeTheme;

class Theme extends HomeTheme
{
    const NAME = 'Egret';
    const VIEW = 'egret';

    public function __construct()
    {
        parent::__construct();
    }

    public function register($is_auth = false)
    {
        parent::register($is_auth);
    }

    protected function registerComposers($is_auth = false)
    {
    }

    public function extensions()
    {
        return [
            // define extension here
        ];
    }

    public function placeholders()
    {
        return [
        ];
    }

    public function widgets()
    {
        return [
        ];
    }
}