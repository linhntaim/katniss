<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-05-21
 * Time: 18:38
 */

namespace Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers;

use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Themes\Extension as BaseExtension;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Controllers\MapMarkerAdminController;
use Katniss\Everdeen\Utils\DataStructure\Menu\Menu;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;

class Extension extends BaseExtension
{
    const NAME = 'google_maps_markers';
    const DISPLAY_NAME = 'Google Maps Markers';
    const DESCRIPTION = 'Enable to manage and embed maps with markers into website';
    const EDITABLE = false;

    public function __construct()
    {
        parent::__construct();
    }

    protected function __init()
    {
        parent::__init();

        _kWidgets([MapMarkerWidget::NAME => MapMarkerWidget::class]);
    }

    public function register()
    {
        addFilter('extra_admin_menu', new CallableObject(function (Menu $menu) {
            if (authUser()->hasRole('admin')) {
                $menu->add( // add a menu item
                    addExtraUrl('admin/google-maps-markers', adminUrl('extra')),
                    trans('google_maps_markers.page_map_markers_title'),
                    '<i class="fa fa-circle-o"></i> <span>', '</span>'
                );
            }
            return $menu;
        }), 'ext:google_maps_markers:menu');

        addExtraRouteResourceTriggers('admin/google-maps-markers', MapMarkerAdminController::class);
    }
}