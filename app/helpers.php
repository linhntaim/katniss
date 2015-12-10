<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-06
 * Time: 17:24
 */

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use Katniss\Models\Helpers\AppOptionHelper;
use Katniss\Models\Helpers\ExtraActions\Hook;
use Katniss\Models\Helpers\ExtraActions\ContentFilter;
use Katniss\Models\Helpers\ExtraActions\ContentPlace;
use Katniss\Models\Helpers\ExtraActions\CallableObject;
use Katniss\Models\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Models\Helpers\AppConfig;
use Illuminate\Support\Facades\Hash;
use Katniss\Models\Themes\Theme;
use Katniss\Models\Themes\WidgetsFacade;
use Katniss\Models\Themes\ExtensionsFacade;
use Katniss\Models\User;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

#region User
function clientIp()
{
    return request()->ip();
}

function isAuth()
{
    static $is_auth;
    if (!isset($is_auth)) {
        $is_auth = auth()->check();
    }
    return $is_auth;
}

function authUser()
{
    static $auth_user;
    if (!isset($auth_user)) {
        $auth_user = isAuth() ? auth()->user() : false;
    }
    return $auth_user;
}

#endregion

#region Generate
/**
 * @param string $name
 * @return string
 */
function wizardKey($name = '')
{
    return Hash::make($name . appKey());
}

/**
 * @param string $key
 * @param string $name
 * @return bool
 */
function isValidWizardKey($key, $name = '')
{
    return Hash::check($name . appKey(), $key);
}

function rdrQueryParam($url)
{
    return AppConfig::KEY_REDIRECT_URL . '=' . urlencode($url);
}

#endregion

#region Locale
function setCurrentLocale($localeCode)
{
    LaravelLocalization::setLocale($localeCode);
}

function fullLocaleCode($localeCode, $separator = '_')
{
    return $localeCode . $separator . allLocale($localeCode, 'country_code');
}

/**
 * @return array
 */
function allLocales()
{
    return config('katniss.locales');
}

/**
 * @return array
 */
function allLocaleCodes()
{
    return array_keys(allLocales());
}

/**
 * @param string $localeCode
 * @param string $property
 * @return array|string|null
 */
function allLocale($localeCode, $property = '')
{
    $locales = allLocales();
    if (empty($locales[$localeCode])) return null;
    return empty($property) || $property == 'all' ? $locales[$localeCode] : $locales[$localeCode][$property];
}

/**
 * @return array
 */
function allSupportedLocales()
{
    return config('laravellocalization.supportedLocales');
}

/**
 * @return array
 */
function allSupportedLocaleCodes()
{
    static $supportedLocaleCodes;
    if (!isset($supportedLocaleCodes)) {
        $supportedLocaleCodes = array_keys(allSupportedLocales());
    }
    return $supportedLocaleCodes;
}

/**
 * @return array
 */
function allSupportedFullLocaleCodes()
{
    $localeCodes = allSupportedLocaleCodes();
    $fullLocaleCodes = [];
    foreach ($localeCodes as $localeCode) {
        $fullLocaleCodes[] = fullLocaleCode($localeCode);
    }
    return $fullLocaleCodes;
}

/**
 * @param string $localeCode
 * @param string $property
 * @return array|string|null
 */
function allSupportedLocale($localeCode, $property = '')
{
    $locales = allSupportedLocales();
    if (empty($locales[$localeCode])) return null;
    return empty($property) || $property == 'all' ? $locales[$localeCode] : $locales[$localeCode][$property];
}

function currentLocaleCode($property = '')
{
    if (empty($property)) {
        return app()->getLocale();
    };
    return allSupportedLocale(app()->getLocale(), $property);
}

function currentFullLocaleCode($separator = '_')
{
    return fullLocaleCode(currentLocaleCode(), $separator);
}

#endregion

#region Generate URL
function transRoute($route)
{
    return LaravelLocalization::transRoute('routes.' . $route);
}

function homeRoute($route)
{
    return transRoute($route);
}

function adminRoute($route)
{
    return transRoute('admin/' . $route);
}

function embedParamsInRoute($route, array $params = [])
{
    if (empty($params)) return $route;
    foreach ($params as $key => $value) {
        $route = str_replace(['{' . $key . '}', '{' . $key . '?}'], $value, $route);
    }
    return $route;
}

function currentPath()
{
    $path = parse_url(currentUrl())['path'];
    return empty($path) ? '/' : $path;
}

function transPath($route = '', array $params = [], $localeCode = null)
{
    if (empty($localeCode)) {
        $localeCode = currentLocaleCode();
    }
    if (empty($route)) {
        return $localeCode;
    }
    return $localeCode . '/' . embedParamsInRoute(trans('routes.' . $route), $params);
}

function homePath($route = '', array $params = [], $localeCode = null)
{
    return transPath($route, $params, $localeCode);
}

function adminPath($route = '', array $params = [], $localeCode = null)
{
    return empty($route) ? homePath('admin', $params, $localeCode) : homePath('admin/' . $route, $params, $localeCode);
}

function currentUrl($localeCode = null)
{
    if (empty($localeCode)) {
        return request()->url();
    }

    return LaravelLocalization::getLocalizedUrl($localeCode, null);
}

function currentFullUrl($localeCode = null)
{
    if (empty($localeCode)) {
        return request()->fullUrl();
    }
    $url_parts = parse_url(request()->fullUrl());
    $localizedUrl = LaravelLocalization::getLocalizedUrl($localeCode, null);
    return $localizedUrl . '?' . $url_parts['query'] . '#' . $url_parts['hash'];
}

function transUrl($route = '', array $params = [], $localeCode = null)
{
    $path = transPath($route, $params, $localeCode = null);
    return url($path);
}

function homeUrl($route = '', array $params = [], $localeCode = null)
{
    return transUrl($route, $params, $localeCode);
}

function adminUrl($route = '', array $params = [], $localeCode = null)
{
    return empty($route) ? homeUrl('admin', $params, $localeCode) : homeUrl('admin/' . $route, $params, $localeCode);
}

function notRootUrl($url)
{
    return $url != homeUrl() && $url != adminUrl();
}

function apiUrl($route, array $params = [], $version = 1)
{
    return url('api/v' . $version . '/' . embedParamsInRoute($route, $params));
}

function redirectUrlAfterLogin(User $user)
{
    $redirect_url = homeURL();
    $overwrite_url = session()->pull(AppConfig::KEY_REDIRECT_URL);
    if (!empty($overwrite_url)) {
        $redirect_url = $overwrite_url;
    } elseif ($user->can('access-admin')) {
        $redirect_url = adminUrl();
    }
    return $redirect_url;
}

#endregion

#region Transform Data
/**
 * @param mixed $input
 * @param string $type
 * @return string
 */
function escapeObject($input, &$type)
{
    $type = 'string';
    if (empty($input)) return '';

    if ($input instanceof Arrayable && !$input instanceof \JsonSerializable) {
        $input = $input->toArray();
    } elseif ($input instanceof Jsonable) {
        $type = 'array';
        $input = $input->toJson();
    }
    if (is_array($input)) {
        $type = 'array';
        return json_encode($input);
    } elseif (is_bool($input)) {
        $type = 'bool';
        $input = $input ? '1' : '0';
    } elseif (is_float($input)) {
        $type = 'float';
    } elseif (is_int($input)) {
        $type = 'int';
    }

    return !is_string($input) ? (string)$input : $input;
}

function fromEscapedObject($input, $type)
{
    switch ($type) {
        case 'array':
            return json_decode($input, true);
        case 'bool':
            return boolval($input);
        case 'int':
            return intval($input);
        case 'float':
            return floatval($input);
        default:
            return $input;
    }
}

function defPr($value, $default)
{
    return empty($value) ? $default : $value;
}

#endregion

#region App Options
function loadOptions()
{
    return AppOptionHelper::load();
}

function setOption($key, $value)
{
    return AppOptionHelper::set($key, $value);
}

function getOption($key, $default = '')
{
    return AppOptionHelper::get($key, $default);
}

#endregion

#region String
/**
 * @param string $input
 * @param int $length
 * @return string
 */
function shorten($input, $length = AppConfig::DEFAULT_SHORTEN_TEXT_LENGTH)
{
    $input = trim($input);
    return htmlspecialchars(trim(str_replace(["&nbsp;", "\r\n", "\n", "\r"], ' ', Str::limit($input, $length))), ENT_QUOTES);
}

/**
 * @param $input
 * @param int $length
 * @return string
 */
function htmlShorten($input, $length = AppConfig::DEFAULT_SHORTEN_TEXT_LENGTH)
{
    $input = trim($input);
    return htmlspecialchars(trim(str_replace(["&nbsp;", "\r\n", "\n", "\r"], ' ', Str::limit(strip_tags($input), $length))), ENT_QUOTES);
}

/**
 * @param string|array $input
 * @return string
 */
function escapeHtml($input)
{
    if (is_array($input)) {
        foreach ($input as &$item) {
            $item = escapeHtml($item);
        }
        return $input;
    }
    return htmlspecialchars(trim(str_replace(['&nbsp;', "\r\n", "\n", "\r"], ' ', strip_tags($input))), ENT_QUOTES);
}

function extractImageUrls($fromString)
{
    if (preg_match_all('/https?:\/\/[^ ]+?(?:\.jpg|\.png|\.gif)/', $fromString, $urls) !== false) {
        return $urls[0];
    }
    return [];
}

/**
 * @param string $input
 * @param string $append
 * @param string $prepend
 * @return string
 */
function toSlug($input, $append = '', $prepend = '')
{
    $slug = Str::slug($input);
    if (!empty($append)) {
        $slug .= '-' . Str::slug($append);
    }
    if (!empty($prepend)) {
        $slug = Str::slug($prepend) . '-' . $slug;
    }
    return $slug;
}

/**
 * @param string $haystack
 * @param array|string $needle
 * @return bool
 */
function startWith($haystack, $needle)
{
    return Str::startsWith($haystack, $needle);
}

#endregion

#region Utilities
/**
 * @param string $password
 * @param User $user
 * @return bool
 */
function isMatchedUserPassword($password, User $user = null)
{
    if (empty($user)) {
        if (isAuth()) {
            $user = authUser();
        }
    }
    return empty($user) ? false : Hash::check($password, $user->password);
}

/**
 * @param $id
 * @param CallableObject $callableObject
 */
function add_action($id, CallableObject $callableObject)
{
    Hook::add($id, $callableObject);
}

function do_action($id, array $params = [])
{
    return Hook::activate($id, $params);
}

/**
 * @param $id
 * @param CallableObject $callableObject
 */
function add_filter($id, CallableObject $callableObject)
{
    ContentFilter::add($id, $callableObject);
}

/**
 * @param string $id
 * @param string|mixed $content
 * @return mixed
 */
function content_filter($id, $content)
{
    return ContentFilter::flush($id, $content);
}

/**
 * @param $id
 * @param CallableObject $callableObject
 */
function add_place($id, CallableObject $callableObject)
{
    ContentPlace::add($id, $callableObject);
}

/**
 * @param string $id
 * @return string
 */
function content_place($id, array $params = [])
{
    return ContentPlace::flush($id, $params);
}

#endregion

#region Theme
/**
 * @param string $file_path
 * @return string
 */
function libraryAsset($file_path = '')
{
    return Theme::libraryAsset($file_path);
}

function cdataOpen()
{
    return '//<![CDATA[';
}

function cdataClose()
{
    return '//]]>';
}

function placeholder($name, $before = '', $after = '')
{
    return WidgetsFacade::display($name, $before, $after);
}

function activatedExtensions()
{
    return ExtensionsFacade::activated();
}

function isActivatedExtension($extension)
{
    return ExtensionsFacade::isActivated($extension);
}

function isStaticExtension($extension)
{
    return ExtensionsFacade::isStatic($extension);
}

function theme_title($titles = '', $use_root = true, $separator = '&raquo;')
{
    return HomeThemeFacade::title($titles, $use_root, $separator);
}

function theme_description($description = '')
{
    return HomeThemeFacade::description($description);
}

function theme_author($author = '')
{
    return HomeThemeFacade::author($author);
}

function theme_application_name($applicationName = '')
{
    return HomeThemeFacade::applicationName($applicationName);
}

function theme_generator($generator = '')
{
    return HomeThemeFacade::generator($generator);
}

function theme_keywords($keywords = '')
{
    return HomeThemeFacade::keywords($keywords);
}

/**
 * @param string|CallableObject $output
 * @param string|integer|null $key
 */
function enqueue_theme_header($output, $key = null)
{
    return HomeThemeFacade::addHeader($output, $key);
}

function dequeue_theme_header($key)
{
    return HomeThemeFacade::removeHeader($key);
}

function theme_header()
{
    return HomeThemeFacade::getHeader();
}

/**
 * @param string|CallableObject $output
 * @param string|integer|null $key
 */
function enqueue_theme_footer($output, $key = null)
{
    return HomeThemeFacade::addFooter($output, $key);
}

function dequeue_theme_footer($key)
{
    return HomeThemeFacade::removeFooter($key);
}

function theme_footer()
{
    return HomeThemeFacade::getFooter();
}

#end region

#region Runtime
function appKey()
{
    return config('app.key');
}

function appName()
{
    return env('APP_NAME');
}

function appDescription()
{
    return env('APP_DESCRIPTION');
}

function appKeywords()
{
    return env('APP_KEYWORDS');
}

function appShortName()
{
    return env('APP_SHORT_NAME');
}

function appVersion()
{
    return env('APP_VERSION');
}

function frameworkVersion()
{
    return 'Laravel ' . \Illuminate\Foundation\Application::VERSION;
}

function appAuthor()
{
    return env('APP_AUTHOR');
}

function appEmail()
{
    return env('APP_EMAIL');
}

function appLogo()
{
    return asset(env('APP_LOGO'));
}

function appDomain()
{
    $parsedUrl = parse_url(homeUrl());
    return $parsedUrl['host'];
}

function appDefaultUserProfilePicture()
{
    return asset(env('APP_DEFAULT_USER_PICTURE'));
}
#endregion