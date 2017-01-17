<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-13
 * Time: 17:27
 */

namespace Katniss\Everdeen\Themes\HomeThemes\WowSkype\Controllers;


use Illuminate\Support\Facades\Validator;
use Katniss\Everdeen\Http\Controllers\Admin\AdminController;
use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Themes\Plugins\GoogleMapsMarkers\Repositories\MapMarkerRepository;

class ThemeAdminController extends AdminController
{
    public function updateOptions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'knowledge_cover_image' => 'required|url',
        ]);

        $rdrResponse = redirect(addExtraUrl('admin/themes/wow_skype/options', adminUrl('extra')));

        if ($validator->fails()) {
            return $rdrResponse->withErrors($validator);
        }

        homeTheme()->options([
            'knowledge_cover_image' => $request->input('knowledge_cover_image'),
            'knowledge_default_article_image' => $request->input('knowledge_default_article_image'),
        ]);

        return $rdrResponse;
    }

    public function options(Request $request)
    {
        $homeTheme = homeTheme();

        return $request->getTheme()->resolveExtraView(
            'home_themes.wow_skype.admin.options',
            trans('wow_skype_theme.page_options_title'),
            trans('wow_skype_theme.page_options_desc'),
            [
                'knowledge_cover_image' => $homeTheme->options('knowledge_cover_image', ''),
                'knowledge_default_article_image' => $homeTheme->options('knowledge_default_article_image', ''),
            ]
        );
    }
}