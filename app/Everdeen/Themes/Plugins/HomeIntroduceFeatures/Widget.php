<?php
namespace Katniss\Everdeen\Themes\Plugins\HomeIntroduceFeatures;

use Katniss\Everdeen\Themes\Plugins\BaseLinks\Widget as BaseLinks;

class Widget extends BaseLinks
{
    const NAME = 'home_introduce_features';
    const DISPLAY_NAME = 'Home Introduce Features';

    public function register()
    {
        enqueueThemeHeader(
            '
<style>
</style>',
            'widget_home_introduce_features'
        );
    }
}
