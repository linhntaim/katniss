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
                'home_email' => $homeTheme->options('home_email', ''),
                'home_hot_line' => $homeTheme->options('home_hot_line', ''),
                'home_description' => $homeTheme->options('home_description', ''),
                'ts_skype_id' => $homeTheme->options('ts_skype_id', ''),
                'ts_skype_name' => $homeTheme->options('ts_skype_name', ''),
                'ts_email' => $homeTheme->options('ts_email', ''),
                'ts_hot_line' => $homeTheme->options('ts_hot_line', ''),
                'social_facebook' => $homeTheme->options('social_facebook', ''),
                'social_twitter' => $homeTheme->options('social_twitter', ''),
                'social_instagram' => $homeTheme->options('social_instagram', ''),
                'social_gplus' => $homeTheme->options('social_gplus', ''),
                'social_youtube' => $homeTheme->options('social_youtube', ''),
                'social_skype' => $homeTheme->options('social_skype', ''),
                'knowledge_cover_image' => $homeTheme->options('knowledge_cover_image', ''),
                'knowledge_default_article_image' => $homeTheme->options('knowledge_default_article_image', ''),
            ]
        );
    }
}