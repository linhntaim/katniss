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
    const VIEW = '';
    const TYPE_ADMIN = 'admin_themes';
    const TYPE_HOME = 'home_themes';

    public static $isAdmin = false;

    /**
     * @return Theme
     */
    public static function byRequest()
    {
        $adminPaths = _k('paths_use_admin_theme');
        $request = request();
        foreach ($adminPaths as $adminPath) {
            $adminPath = homePath($adminPath);
            if ($request->is($adminPath, $adminPath . '/*')) {
                self::$isAdmin = true;
                return app('admin_theme');
            }
        }
        return app('home_theme');
    }

    public static function errorRequest(HttpException $exception)
    {
        return Theme::byRequest()->errorResponse($exception);
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
        $this->header = new TextQueue();
        $this->footer = new TextQueue();
        $this->libJsQueue = new JsQueue();
        $this->extJsQueue = new JsQueue();
        $this->libCssQueue = new CssQueue();
        $this->extCssQueue = new CssQueue();
        $this->titleRoot = appName();
        $this->title = appName();
        $this->description = appDescription();
        $this->applicationName = appName();
        $this->author = appAuthor() . ' (' . appEmail() . ')';
        $this->generator = appName() . ' ' . appVersion() . ' (' . frameworkVersion() . ')';
        $this->keywords = appKeywords();
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

            add_filter('open_graph_tags_before_render', new CallableObject(function ($data) {
                $data['og:title'] = theme_title();
                return $data;
            }));
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

            add_filter('open_graph_tags_before_render', new CallableObject(function ($data) {
                $data['og:description'] = theme_description();
                return $data;
            }));
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

        enqueue_theme_header(Html5::metaName('generator', $this->generator), 'framework_version');

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

    protected function errorResponse(HttpException $exception) {
        $status = $exception->getStatusCode();
        $view = $this->error($status); // specific error
        $existed = view()->exists($view);
        if (!$existed) {
            $view = $this->error(); // common error
            $existed = view()->exists($view);
        }
        if ($existed) {
            return response()->view($view, ['exception' => $exception], $status, $exception->getHeaders());
        }

        return false;
    }
}