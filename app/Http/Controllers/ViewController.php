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

    protected $viewPath;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->theme = Theme::byRequest();
        $this->theme->register($this->isAuth);
        $this->globalViewParams = [
            'site_locale' => $this->localeCode,
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
            'is_auth' => $this->isAuth,
            'auth_user' => $this->authUser,
            'session_id' => $request->session()->getId(),
            'successes' => $request->session()->has('successes') ?
                collect((array)$request->session()->get('successes')) : collect([]),
            'info' => $request->session()->has('info') ?
                collect((array)$request->session()->get('info')) : collect([]),
            'max_upload_file_size' => maxUploadFileSize()
        ];
        foreach ($this->globalViewParams as $key => $value) {
            view()->share($key, $value);
        }
    }

    protected function themePage($name)
    {
        return $this->theme->page($name);
    }

    protected function _view($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath), $data, $mergeData);
    }

    protected function _any($view, $data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.' . $view), $data, $mergeData);
    }

    protected function _list($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.list'), $data, $mergeData);
    }

    protected function _add($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.add'), $data, $mergeData);
    }

    protected function _edit($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.edit'), $data, $mergeData);
    }

    protected function _detail($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.detail'), $data, $mergeData);
    }
}
