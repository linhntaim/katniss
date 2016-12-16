<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-30
 * Time: 16:16
 */

namespace Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories\MapMarkerRepository;

class MapMarkerWidget extends DefaultWidget
{
    const NAME = 'google_maps_markers.widget';
    const DISPLAY_NAME = 'Google Maps Marker';

    protected $mapMarkerId = '';

    protected $mapMarker = '';
    protected $mapMarkerName = '';
    protected $mapMarkerDescription = '';
    protected $mapMarkerLatitude = '';
    protected $mapMarkerLongitude = '';

    protected function __init()
    {
        parent::__init();

        $this->mapMarkerId = $this->getProperty('map_marker_id');

        if (!empty($this->mapMarkerId)) {
            $mapMarkerRepository = new MapMarkerRepository($this->mapMarkerId);
            $this->mapMarker = $mapMarkerRepository->model();
            $mapMarkerData = $this->mapMarker->data;
            $this->mapMarkerName = !empty($this->mapMarker->name) ? $this->mapMarker->name : $mapMarkerData->address;
            $this->mapMarkerDescription = $this->mapMarker->description;
            $this->mapMarkerLatitude = $mapMarkerData->lat;
            $this->mapMarkerLongitude = $mapMarkerData->lng;
        }
    }

    public function register()
    {
        if (!empty($this->mapMarkerId)) {
            enqueueThemeHeader('<style>.widget-google-maps-markers .map{width: 100%;height: 300px;margin:5px 0}</style>', 'google_maps_markers_widget');
            enqueueThemeFooter('<script src="' . _kExternalLink('google-maps-js-api') . '?key=' . env('GOOGLE_MAPS_API_KEY') . '&language=' . currentLocaleCode() . '&region=' . settings()->country . '"></script>', 'google_maps_js_api');
            enqueueThemeFooter('<script src="' . libraryAsset('google_maps_markers.js') . '"></script>', 'google_maps_markers_js');
            enqueueThemeFooter('<script>$(function(){
    new GoogleMapsMarkers($(\'#' . $this->getHtmlId() . ' .map\'), {
        zoom: 15,
        markCenter: true,
        center: {
            lat: ' . $this->mapMarkerLatitude . ',
            lng: ' . $this->mapMarkerLongitude . '
        },
        centerName: \'' . $this->mapMarkerName . '\'
    });
});</script>', $this->getHtmlId());
        }
    }

    public function viewAdminParams()
    {
        $mapMarkerRepository = new MapMarkerRepository();

        return array_merge(parent::viewAdminParams(), [
            'map_marker_id' => $this->mapMarkerId,
            'map_markers' => $mapMarkerRepository->getAll(),
        ]);
    }

    public function viewHomeParams()
    {
        return array_merge(parent::viewHomeParams(), [
            'map_marker' => $this->mapMarker,
            'marker_name' => $this->mapMarkerName,
            'marker_description' => $this->mapMarkerDescription,
            'marker_latitude' => $this->mapMarkerLatitude,
            'marker_longitude' => $this->mapMarkerLongitude,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'map_marker_id'
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'map_marker_id' => 'required|exists:map_markers,id',
        ]);
    }
}