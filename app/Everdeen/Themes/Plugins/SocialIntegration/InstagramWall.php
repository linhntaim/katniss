<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-11-14
 * Time: 18:36
 */

namespace Katniss\Everdeen\Themes\Plugins\SocialIntegration;

use Katniss\Everdeen\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Everdeen\Themes\Plugins\SocialIntegration\Extension as SocialIntegrationExtension;
use Larabros\Elogram\Client;
use League\OAuth2\Client\Token\AccessToken;

class InstagramWall extends DefaultWidget
{
    const WIDGET_NAME = 'instagram_wall';
    const WIDGET_DISPLAY_NAME = 'Instagram Wall';

    protected $username;
    protected $numOfColumns;
    protected $numOfMedia;

    protected $shared;

    public function __init()
    {
        parent::__init();

        $this->username = defPr($this->getProperty('username'), '');
        $this->numOfColumns = defPr($this->getProperty('num_of_columns'), 3);
        $this->numOfMedia = defPr($this->getProperty('num_of_media'), 12);

        $this->shared = Extension::getSharedData(SocialIntegrationExtension::EXTENSION_NAME);
    }

    public function register()
    {
        enqueue_theme_header(
            '<style>.widget-instagram-wall ul.media-list li.media-item{width: calc(100%/' . $this->numOfColumns . ')}</style>',
            'instagram_wall_style'
        );
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'username' => $this->username,
            'num_of_columns' => $this->numOfColumns,
        ]);
    }

    public function viewHomeParams()
    {
        // instagram client
        $client = new Client($this->shared->instagramClientId, $this->shared->instagramClientSecret);
        $client->setAccessToken(new AccessToken(['access_token' => $this->shared->instagramAccessToken]));
        $instagramUser = $client->users()->find($this->username);
        $instagramMedia = [];
        if (!empty($instagramUser)) {
            $instagramUser = $instagramUser->get();
            $instagramMedia = $client->users()->getMedia($instagramUser['id'], $this->numOfMedia)->get();
        }
        return array_merge(parent::viewHomeParams(), [
            'instagram_media' => $instagramMedia,
            'instagram_user' => $instagramUser,
        ]);
    }

    public function render()
    {
        return $this->renderByTemplate();
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'username',
            'num_of_columns'
        ]);
    }

    public function validationRules()
    {
        return array_merge(parent::validationRules(), [
            'username' => 'required',
            'num_of_columns' => 'required|integer',
        ]);
    }
}