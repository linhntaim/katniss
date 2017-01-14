<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-01-14
 * Time: 13:03
 */

namespace Katniss\Everdeen\Themes\HomeThemes\WowSkype\Plugins\Demo;

use Katniss\Everdeen\Themes\Extension as BaseExtension;

class Demo extends BaseExtension
{
    const NAME = 'demo';
    const DISPLAY_NAME = 'Demo';
    const DESCRIPTION = 'Demo';
    const EDITABLE = true;

    public function register()
    {
        enqueueThemeFooter('ấdfasdfasdfasd', 'demo');
    }
}