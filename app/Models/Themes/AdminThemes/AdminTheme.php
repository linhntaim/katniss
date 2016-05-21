<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Models\Themes\AdminThemes;


use Katniss\Models\Themes\ExtensionsFacade;
use Katniss\Models\Themes\Theme;

abstract class AdminTheme extends Theme
{
    public function __construct()
    {
        parent::__construct(Theme::TYPE_ADMIN);
    }

    public function register($is_auth = false)
    {
        $this->registerExtensions($is_auth);

        parent::register($is_auth);
    }

    protected function registerExtensions($is_auth = false)
    {
        ExtensionsFacade::register();
    }

    public function extensions()
    {
        return [];
    }
}