<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-10-28
 * Time: 04:50
 */

namespace Katniss\Models\Themes;

use Illuminate\Support\Facades\Request;
use Katniss\Models\Helpers\AppConfig;
use Katniss\Models\Helpers\ExtraActions\CallableObject;
use Katniss\Models\Helpers\HtmlTag\Html5;

abstract class Theme
{
    const NAME = '';
    const VIEW = '';
    const TYPE_ADMIN = 'admin_themes';
    const TYPE_HOME = 'home_themes';

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
     * @var array
     */
    protected $header;

    /**
     * @var array
     */
    protected $footer;

    protected function __construct($type)
    {
        $this->name = $this::NAME;
        $this->view = $this::VIEW;
        $this->type = $type;
        $this->viewPath = empty($type) ? $this->view . '.' : $this->type . '.' . $this->view . '.';
        $this->assetPath = empty($type) ? 'assets/' . $this->view . '/' : 'assets/' . $this->type . '/' . $this->view . '/';
        $this->header = [];
        $this->footer = [];
        $this->titleRoot = appName();
        $this->title = appName();
        $this->description = appDescription();
        $this->applicationName = appName();
        $this->author = appAuthor() . '(' . appEmail() . ')';
        $this->generator = frameworkVersion();
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

    public function page($name)
    {
        return $this->pagePath($name);
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
        if (empty($key)) {
            $key = count($this->header);
        }

        $this->header[$key] = $output;
    }

    public function removeHeader($key)
    {
        unset($this->header[$key]);
    }

    /**
     * @param string|CallableObject $output
     * @param string|integer|null $key
     */
    public function addFooter($output, $key = null)
    {
        if (empty($key)) {
            $key = count($this->footer);
        }

        $this->footer[$key] = $output;
    }

    public function removeFooter($key)
    {
        unset($this->footer[$key]);
    }

    public function getHeader()
    {
        $output = '<!-- Extra header -->';
        foreach ($this->header as $header) {
            if (is_a($header, CallableObject::class)) {
                $output .= PHP_EOL . $header->execute();
            } else {
                $output .= PHP_EOL . $header;
            }
        }
        return $output . PHP_EOL . '<!-- End extra header -->';
    }

    public function getFooter()
    {
        $output = '<!-- Extra footer -->';
        foreach ($this->footer as $footer) {
            if (is_a($footer, CallableObject::class)) {
                $output .= PHP_EOL . $footer->execute();
            } else {
                $output .= PHP_EOL . $footer;
            }
        }
        return $output . PHP_EOL . '<!-- End extra footer -->';
    }

    public function register($is_auth = false)
    {
        enqueue_theme_header(Html5::metaName('generator', $this->generator), 'framework_version');

        $this->registerComposers($is_auth);
    }

    protected function registerComposers($is_auth = false)
    {
    }

    public static function byRequest()
    {
        $admin_paths = config('katniss.paths_use_admin_theme');
        $request = request();
        foreach ($admin_paths as $path) {
            $path = homePath($path);
            if ($request->is($path, $path . '/*')) {
                return app('admin_theme');
            }
        }
        return app('home_theme');
    }
}