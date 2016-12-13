<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-14
 * Time: 00:28
 */

namespace Katniss\Everdeen\Themes\HomeThemes\ExampleTheme\Composers;


use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Katniss\Everdeen\Themes\Plugins\ContactForm\Extension as ContactFormExtension;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Extension as GoogleMapsMarkersExtension;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories\MapMarkerRepository;

class ContactTemplateComposer
{
    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $postTemplate = new \stdClass();
        $postTemplate->showContactForm = isActivatedExtension(ContactFormExtension::NAME);
        $postTemplate->contactForm = $postTemplate->showContactForm ?
            ContactFormExtension::htmlContactForm() : null;
        $postTemplate->showMapMarker = isActivatedExtension(GoogleMapsMarkersExtension::NAME);
        $postTemplate->mapMarker = null;
        if ($postTemplate->showMapMarker) {
            $postTemplate->mapMarker = $this->getMapMarker();
        }
        $view->with('post_template', $postTemplate);
    }

    protected function getMapMarker()
    {
        $themeOptions = getOption('theme_example', []);
        if (empty($themeOptions['default_map_marker_id'])) return null;

        $mapMarkerRepository = new MapMarkerRepository($themeOptions['default_map_marker_id']);
        $mapMarker = $mapMarkerRepository->model();
        $mapMarkerData = $mapMarker->data;
        $mapMarkerName = !empty($mapMarker->name) ? $mapMarker->name : $mapMarkerData->address;
        $mapMarkerDescription = $mapMarker->description;
        $mapMarkerLatitude = $mapMarkerData->lat;
        $mapMarkerLongitude = $mapMarkerData->lng;

        enqueueThemeHeader('<style>#default-map-marker .map{width: 100%;height: 300px;margin:5px 0}</style>', 'theme_example_contact_template');
        enqueueThemeFooter('<script src="' . _kExternalLink('google-maps-js-api') . '?key=' . env('GOOGLE_MAPS_API_KEY') . '&language=' . currentLocaleCode() . '&region=' . settings()->country . '"></script>', 'google_maps_js_api');
        enqueueThemeFooter('<script src="' . libraryAsset('google_maps_markers.js') . '"></script>', 'google_maps_markers_js');
        enqueueThemeFooter('<script>$(function(){
    new GoogleMapsMarkers($(\'#default-map-marker .map\'), {
        zoom: 15,
        markCenter: true,
        center: {
            lat: ' . $mapMarkerLatitude . ',
            lng: ' . $mapMarkerLongitude . '
        },
        centerName: \'' . $mapMarkerName . '\'
    });
});</script>', 'default-map-marker');

        return new HtmlString('<div id="default-map-marker"><div class="map"></div></div>');
    }
}