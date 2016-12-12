<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-06
 * Time: 09:28
 */

namespace Katniss\Everdeen\Http;

use Illuminate\Http\Request as BaseRequest;
use Katniss\Everdeen\Themes\Theme;

class Request extends BaseRequest
{
    public $isAuth;
    public $authUser;

    /**
     * @var Theme
     */
    protected $theme;

    public function setAuth($isAuth, $authUser)
    {
        $this->isAuth = $isAuth;
        $this->authUser = $isAuth ? $authUser : null;
    }

    /**
     * @return Theme
     */
    public function theme()
    {
        if (empty($this->theme)) {
            $this->resolveTheme();
        }
        return $this->theme;
    }

    protected function resolveTheme()
    {
        $this->theme = Theme::byRequest(); // register theme
        $viewParams = [
            'site_locale' => currentLocaleCode(),
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
            'is_auth' => isAuth(),
            'auth_user' => authUser(),
            'max_upload_file_size' => maxUploadFileSize()
        ];
        if ($this->hasSession()) {
            $session = $this->session();
            $viewParams['session_id'] = $session->getId();
            $viewParams['successes'] = $session->has('successes') ?
                collect((array)$session->get('successes')) : collect([]);
            $viewParams['info'] = $session->has('info') ?
                collect((array)$session->get('info')) : collect([]);
        }
        $viewParams = $this->theme->viewParams($viewParams);
        foreach ($viewParams as $key => $value) {
            view()->share($key, $value);
        }
    }
}