<?php

namespace Katniss\Everdeen\Http\Controllers;

use Illuminate\Http\Request;
use Katniss\Everdeen\Themes\Theme;
use Katniss\Everdeen\Utils\AppConfig;

class ViewController extends KatnissController
{
    /**
     * @var \Katniss\Everdeen\Themes\Theme
     */
    protected $theme;

    /**
     * @var array
     */
    protected $globalViewParams;

    /**
     * @var string
     */
    protected $viewPath;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->middleware(function (Request $request, $next) {
            $session = $request->session();

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
                'session_id' => $session->getId(),
                'successes' => $session->has('successes') ?
                    collect((array)$session->get('successes')) : collect([]),
                'info' => $session->has('info') ?
                    collect((array)$session->get('info')) : collect([]),
                'max_upload_file_size' => maxUploadFileSize()
            ];
            foreach ($this->globalViewParams as $key => $value) {
                view()->share($key, $value);
            }
            return $next($request);
        });
    }

    protected function themePage($name)
    {
        return $this->theme->page($name);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _view($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath), $data, $mergeData);
    }

    /**
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _any($view, $data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.' . $view), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _index($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.index'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _create($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.create'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _edit($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.edit'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _show($data = [], $mergeData = [])
    {
        return view($this->themePage($this->viewPath . '.show'), $data, $mergeData);
    }

    protected function _rdrUrl(Request $request, $url, &$rdrUrl, &$errorRdrUrl)
    {
        $errorRdrUrl = $rdrUrl = $url;
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_URL, '');
        if (!empty($rdr)) {
            $errorRdrUrl = $rdrUrl = $rdr;
        }
        $rdr = $request->session()->pull(AppConfig::KEY_REDIRECT_ON_ERROR_URL, '');
        if (!empty($rdr)) {
            $errorRdrUrl = $rdr;
        }
    }
}
