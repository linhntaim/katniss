<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Everdeen\Themes;

use Katniss\Everdeen\Http\Controllers\ViewController;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Utils\HtmlTag\Html5;
use Katniss\Everdeen\Utils\SettingsFacade;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class Theme
{
    const NAME = '';
    const DISPLAY_NAME = '';
    const VIEW = '';
    const TYPE_ADMIN = 'admin_themes';
    const TYPE_HOME = 'home_themes';

    public static $isAdmin = false;
    public static $onWebApi = false;

    /**
     * @return Theme
     */
    public static function byRequest()
    {
        $checkPath = checkPath();
        if ($checkPath->api) {
            return null;
        }
        self::$onWebApi = $checkPath->webApi;
        self::$isAdmin = $checkPath->admin;
        $theme = self::$isAdmin ? app('admin_theme') : app('home_theme');
        $theme->register(isAuth());
        return $theme;
    }

    protected $name;
    protected $view;
    protected $type;
    protected $viewPath;
    protected $assetPath;

    protected $titleRoot;
    protected $title;
    protected $description;
    protected $applicationName;
    protected $author;
    protected $generator;
    protected $keywords;

    protected $viewParams;

    /**
     * @var TextQueue
     */
    protected $header;

    /**
     * @var TextQueue
     */
    protected $footer;

    protected $libJsQueue;
    protected $extJsQueue;
    protected $libCssQueue;
    protected $extCssQueue;

    protected function __construct($type)
    {
        $this->name = $this::NAME;
        $this->view = $this::VIEW;
        $this->type = $type;
        $this->viewPath = empty($type) ? $this->view . '.' : $this->type . '.' . $this->view . '.';
        $this->assetPath = empty($type) ? 'assets/' . $this->view . '/' : 'assets/' . $this->type . '/' . $this->view . '/';
        $this->titleRoot = appName();
        $this->title = appName();
        $this->description = appDescription();
        $this->applicationName = appName();
        $this->author = appAuthor() . ' (' . appEmail() . ')';
        $this->generator = appName() . ' ' . appVersion() . ' (' . frameworkVersion() . ')';
        $this->keywords = appKeywords();
        $this->viewParams = [];
        $this->header = new TextQueue();
        $this->footer = new TextQueue();
        $this->libJsQueue = new JsQueue();
        $this->extJsQueue = new JsQueue();
        $this->libCssQueue = new CssQueue();
        $this->extCssQueue = new CssQueue();
    }

    protected function masterPath($name)
    {
        return $this->viewPath . 'master.' . $name;
    }

    protected function pagePath($name)
    {
        return $this->viewPath . 'pages.' . $name;
    }

    protected function errorPath($name = 'common')
    {
        return $this->viewPath . 'errors.' . $name;
    }

    public function page($name)
    {
        return $this->pagePath($name);
    }

    public function error($name = 'common')
    {
        return $this->errorPath($name);
    }

    public function asset($file_path = '')
    {
        return asset($this->assetPath . $file_path);
    }

    public function imageAsset($file_path)
    {
        return $this->asset('img/' . $file_path);
    }

    public function cssAsset($file_path)
    {
        return $this->asset('css/' . $file_path);
    }

    public function jsAsset($file_path)
    {
        return $this->asset('js/' . $file_path);
    }

    public function pluginAsset($file_path)
    {
        return $this->asset('plugins/' . $file_path);
    }

    public function libAsset($file_path = '')
    {
        return self::libraryAsset($file_path);
    }

    static function libraryAsset($file_path = '')
    {
        return asset('assets/libraries/' . $file_path);
    }

    /**
     * @param string $author
     * @return mixed|string
     */
    public function titleRoot($title = '')
    {
        if (!empty($title)) {
            $this->titleRoot = escapeHtml($title);
        }
        return $this->titleRoot;
    }

    /**
     * @param string|array $titles
     * @param string $separator
     * @return string
     */
    public function title($titles = '', $use_root = true, $separator = '&raquo;')
    {
        if (!empty($titles)) {
            $separator = ' ' . trim($separator) . ' ';
            $titles = (array)$titles;
            if ($use_root) {
                array_unshift($titles, $this->titleRoot);
            }
            $this->title = implode($separator, $titles);

            addFilter('open_graph_tags_before_render', new CallableObject(function ($data) {
                $data['og:title'] = themeTitle();
                return $data;
            }), 'theme:title');
        }
        return $this->title;
    }

    /**
     * @param string $description
     * @return string
     */
    public function description($description = '')
    {
        if (!empty($description)) {
            $this->description = htmlShorten($description, AppConfig::MEDIUM_SHORTEN_TEXT_LENGTH);

            addFilter('open_graph_tags_before_render', new CallableObject(function ($data) {
                $data['og:description'] = themeDescription();
                return $data;
            }), 'theme:description');
        }
        return $this->description;
    }

    /**
     * @param string $author
     * @return mixed|string
     */
    public function author($author = '')
    {
        if (!empty($author)) {
            $this->author = escapeHtml($author);
        }
        return $this->author;
    }

    /**
     * @param string $author
     * @return mixed|string
     */
    public function applicationName($applicationName = '')
    {
        if (!empty($applicationName)) {
            $this->applicationName = escapeHtml($applicationName);
        }
        return $this->applicationName;
    }

    /**
     * @param string $author
     * @return mixed|string
     */
    public function generator($generator = '')
    {
        if (!empty($generator)) {
            $this->generator = escapeHtml($generator);
        }
        return $this->generator;
    }

    /**
     * @param string|array $keywords
     * @return string
     */
    public function keywords($keywords = '')
    {
        if (!empty($keywords)) {
            $keywords = (array)escapeHtml($keywords);
            $this->keywords = $keywords . ',' . implode(',', $keywords);
        }
        return $this->keywords;
    }

    /**
     * @param array|null $params
     * @return array
     */
    public function viewParams($params = null)
    {
        if ($params != null) {
            $this->viewParams = $params;
        }
        return $this->viewParams;
    }

    /**
     * @param string|CallableObject $output
     * @param string|integer|null $key
     */
    public function addHeader($output, $key = null)
    {
        $this->header->add($key, $output);
    }

    public function removeHeader($key)
    {
        $this->header->remove($key);
    }

    /**
     * @param string|CallableObject $output
     * @param string|integer|null $key
     */
    public function addFooter($output, $key = null)
    {
        $this->footer->add($key, $output);
    }

    public function removeFooter($key)
    {
        $this->footer->remove($key);
    }

    public function getHeader()
    {
        return wrapContent($this->header->flush(false),
            '<!-- Extra header -->' . PHP_EOL,
            PHP_EOL . '<!-- End extra header -->');
    }

    public function getFooter()
    {
        return wrapContent($this->footer->flush(false),
            '<!-- Extra footer -->' . PHP_EOL,
            PHP_EOL . '<!-- End extra footer -->');
    }

    public function getLibCss()
    {
        return wrapContent($this->libCssQueue->flush(false),
            '<!-- Lib styles -->' . PHP_EOL,
            PHP_EOL . '<!-- End lib styles -->');
    }

    public function getExtCss()
    {
        return wrapContent($this->extCssQueue->flush(false),
            '<!-- Extended styles -->' . PHP_EOL,
            PHP_EOL . '<!-- End extended styles -->');
    }

    public function getLibJs()
    {
        return wrapContent($this->libJsQueue->flush(false),
            '<!-- Lib scripts -->' . PHP_EOL,
            PHP_EOL . '<!-- End lib scripts -->');
    }

    public function getExtJs()
    {
        return wrapContent($this->extJsQueue->flush(false),
            '<!-- Extended scripts -->' . PHP_EOL,
            PHP_EOL . '<!-- End extended scripts -->');
    }

    public function register($is_auth = false)
    {
        // priority of registering: extension > widget > others
        $this->registerExtensions($is_auth);
        $this->registerWidgets($is_auth);

        enqueueThemeHeader(Html5::metaName('generator', $this->generator), 'framework_version');

        $this->registerComposers($is_auth);
        $this->registerLibStyles($is_auth);
        $this->registerExtStyles($is_auth);
        $this->registerLibScripts($is_auth);
        $this->registerExtScripts($is_auth);
    }

    protected function registerExtensions($is_auth = false)
    {
        ExtensionsFacade::init();
        ExtensionsFacade::register();
    }

    protected function registerWidgets($is_auth = false)
    {
        WidgetsFacade::init();
    }

    protected function registerComposers($is_auth = false)
    {
    }

    protected function registerLibStyles($is_auth = false)
    {
    }

    protected function registerExtStyles($is_auth = false)
    {
    }

    protected function registerLibScripts($is_auth = false)
    {
    }

    protected function registerExtScripts($is_auth = false)
    {
        $userApp = app('user_app');
        $this->extJsQueue->add('global-vars', [
            'KATNISS_THEME_PATH' => $this->asset(),
            'KATNISS_REQUEST_TOKEN' => csrf_token(),
//            'KATNISS_REQUEST_TOKEN' => csrf_token(),
            'KATNISS_APP' => $userApp->toJson(),
            'KATNISS_SETTINGS' => SettingsFacade::toJson(),
            'KATNISS_API_URL' => apiUrl(null, [], $userApp->version),
            'KATNISS_WEB_API_URL' => webApiUrl(),
            'KATNISS_SESSION_LIFETIME' => sessionLifetime(),
            'KATNISS_USER' => isAuth() ? authUser()->toJson() : 'false',
        ], JsQueue::TYPE_VAR, ['KATNISS_APP', 'KATNISS_SETTINGS', 'KATNISS_USER']);
    }

    public function resolveErrorView($code, $originalPath = null)
    {
        $viewInstance = view();
        $view = $this->error($code);
        if (!$viewInstance->exists($view)) {
            $view = $this->error('common');
            if (!$viewInstance->exists($view)) {
                $view = 'errors.' . $code;
                if (!$viewInstance->exists($view)) {
                    $view = 'errors.common';
                    if (!$viewInstance->exists($view)) {
                        return false;
                    }
                }
            }
        }
        return $view;
    }
}