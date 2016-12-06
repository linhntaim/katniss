<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Everdeen\Themes\AdminThemes;


use Katniss\Everdeen\Themes\ExtensionsFacade;
use Katniss\Everdeen\Themes\Theme;

abstract class AdminTheme extends Theme
{
    public function __construct()
    {
        parent::__construct(Theme::TYPE_ADMIN);
    }

    public function extensions()
    {
        return [];
    }

    protected function registerExtScripts($is_auth = false)
    {
        parent::registerExtScripts($is_auth);

        $this->extJsQueue->add('global-app-script', libraryAsset('katniss.admin.js'));
    }
}