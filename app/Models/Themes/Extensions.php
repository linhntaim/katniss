<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 11:38
 */

namespace Katniss\Models\Themes;

use Katniss\Models\Themes\HomeThemes\HomeThemeFacade;

class Extensions
{
    private $statics;

    private $defines;

    private $activated;

    private $adminExcepts;

    public function __construct()
    {
        $this->defines = array_merge(config('katniss.extensions'), HomeThemeFacade::extensions());
        $this->statics = config('katniss.static_extensions');
        $this->activated = array_unique(array_merge((array)getOption('activated_extensions', []), $this->staticExtensions()));
        $this->adminExcepts = config('katniss.admin_except_extensions');
    }

    public function register()
    {
        $extensions = $this->activated();
        foreach ($extensions as $extension) {
            if (!in_admin() || !in_array($extension, $this->adminExcepts)) {
                $extensionClass = $this->extensionClass($extension);
                if (!empty($extensionClass) && class_exists($extensionClass)) {
                    $extension = new $extensionClass();
                    $extension->register();
                }
            }
        }
    }

    public function all()
    {
        return $this->defines;
    }

    public function extensionClass($name)
    {
        static $extensions;
        if (empty($extensions)) {
            $extensions = $this->all();
        }
        return empty($extensions[$name]) ? null : $extensions[$name];
    }

    public function staticExtensions()
    {
        return $this->statics;
    }

    public function isStatic($extension)
    {
        return in_array($extension, $this->statics);
    }

    public function activated()
    {
        return $this->activated;
    }

    public function isActivated($extension)
    {
        return in_array($extension, $this->activated);
    }
}