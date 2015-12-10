<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 02:57
 */

namespace Katniss\Models\Themes\AdminThemes;

use Illuminate\Support\Facades\Facade;

class AdminThemeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'admin_theme';
    }
}