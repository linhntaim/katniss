<?php

namespace Katniss\Http\Controllers;

use Illuminate\Http\Request;
use Katniss\Models\Themes\Theme;

class ViewController extends KatnissController
{
    /**
     * @var \Katniss\Models\Themes\Theme
     */
    protected $theme;

    protected $globalViewParams;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->theme = Theme::byRequest();
        $this->theme->register($this->is_auth);
        $this->globalViewParams = [
            'site_locale' => $this->locale,
            'site_version' => appVersion(),
            'site_name' => appName(),
            'site_logo' => appLogo(),
            'site_keywords' => appKeywords(),
            'site_short_name' => appShortName(),
            'site_description' => appDescription(),
            'site_author' => appAuthor(),
            'site_email' => appEmail(),
            'site_domain' => appDomain(),
            'site_home_url' => homeUrl(),
            'is_auth' => $this->is_auth,
            'auth_user' => $this->auth_user,
            'session_id' => $request->session()->getId(),
        ];
        foreach ($this->globalViewParams as $key => $value) {
            view()->share($key, $value);
        }
    }

    protected function themePage($name)
    {
        return $this->theme->page($name);
    }
}
