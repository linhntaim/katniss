<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-06
 * Time: 17:24
 */

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Facades\Agent;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\AppOptionHelper;
use Katniss\Everdeen\Utils\DateTimeHelper;
use Katniss\Everdeen\Utils\ExtraActions\Hook;
use Katniss\Everdeen\Utils\ExtraActions\ContentFilter;
use Katniss\Everdeen\Utils\ExtraActions\ContentPlace;
use Katniss\Everdeen\Utils\ExtraActions\CallableObject;
use Katniss\Everdeen\Utils\NumberFormatHelper;
use Katniss\Everdeen\Models\User;
use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Everdeen\Themes\AdminThemes\AdminThemeFacade;
use Katniss\Everdeen\Themes\Theme;
use Katniss\Everdeen\Themes\WidgetsFacade;
use Katniss\Everdeen\Themes\ExtensionsFacade;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

#region Katniss Configuration
/**
 * Get katniss configuration
 *
 * @param string|null $key
 * @param mixed $default
 * @return string|mixed
 */
function _k($key = null, $default = null)
{
    if (is_null($key)) {
        return config('katniss');
    }

    if (is_array($key)) {
        $keyTemp = [];
        foreach ($key as $k => $v) {
            $keyTemp['katniss.' . $k] = $v;
        }
        return config($keyTemp);
    }

    return config('katniss.' . $key, $default);
}

function _kExternalLink($key = null, $default = null)
{
    if (is_null($key)) {
        return config('katniss.external_links');
    }

    if (is_array($key)) {
        $keyTemp = [];
        foreach ($key as $k => $v) {
            $keyTemp['katniss.external_links.' . $k] = $v;
        }
        return config($keyTemp);
    }

    return config('katniss.external_links.' . $key, $default);
}

function _kWidgets($key = null, $default = null)
{
    if (is_null($key)) {
        return config('katniss.widgets');
    }

    if (is_array($key)) {
        $keyTemp = [];
        foreach ($key as $k => $v) {
            $keyTemp['katniss.widgets.' . $k] = $v;
        }
        return config($keyTemp);
    }

    return config('katniss.widgets.' . $key, $default);
}

#endregion

#region Detect Client
function isPhoneClient()
{
    return Agent::isPhone();
}

function isDesktopClient()
{
    return Agent::isDesktop();
}

function isMobileClient()
{
    return Agent::isMobile();
}

function isTabletClient()
{
    return Agent::isTablet();
}

#endregion

#region User
function clientIp()
{
    return request()->ip();
}

function isAuth()
{
    return auth()->check();
}

function authUser()
{
    return isAuth() ? auth()->user() : false;
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

function methodParam($method)
{
    return '_method=' . $method;
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

function supportedLocalesAsOptions()
{
    $selected_locale = currentLocaleCode();
    $options = '';
    foreach (allSupportedLocales() as $localeCode => $properties) {
        $options .= '<option value="' . $localeCode . '"' . ($localeCode == $selected_locale ? ' selected' : '') . '>' . $properties['native'] . '</option>';
    }
    return $options;
}

function supportedLocalesAsInputTabs()
{
    return array_merge([
        AppConfig::INTERNATIONAL_LOCALE_CODE => [
            'native' => trans('label.default'),
        ]
    ], allSupportedLocales());
}

function supportedLocaleCodesOfInputTabs()
{
    $locales = allSupportedLocaleCodes();
    array_unshift($locales, AppConfig::INTERNATIONAL_LOCALE_CODE);
    return $locales;
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
function checkPath($request = null)
{
    if (empty($request)) {
        $request = request();
    }

    $return = new stdClass();
    $return->locale = in_array($request->segment(1), allSupportedLocaleCodes());
    $return->api = false;
    $return->webApi = false;
    $return->admin = false;
    $return->home = false;

    $apiPath = 'api'; // can be changed
    if ($request->is($apiPath, $apiPath . '/*')) {
        $return->api = true;
        return $return;
    }
    $webApiPath = 'web-api'; // can be changed

    $return->webApi = $request->is($webApiPath, $webApiPath . '/*');

    $adminPaths = _k('paths_use_admin_theme');
    foreach ($adminPaths as $adminPath) {
        $adminPath = !$return->webApi ? homePath($adminPath) : $webApiPath . '/' . $adminPath;
        if ($request->is($adminPath, $adminPath . '/*')) {
            $return->admin = true;
            break;
        }
    }

    $return->home = !$return->admin;

    return $return;
}

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
    $route = trans('routes.' . $route, [], '', $localeCode);
    return $localeCode . '/' . embedParamsInRoute($route, $params);
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
    $path = transPath($route, $params, $localeCode);
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

function meUrl($route = '', array $params = [], $localeCode = null)
{
    return empty($route) ? homeUrl('me', $params, $localeCode) : homeUrl('me/' . $route, $params, $localeCode);
}

function notRootUrl($url)
{
    return $url != homeUrl() && $url != adminUrl();
}

function rootUrl()
{
    return url('');
}

function apiUrl($route = '', array $params = [], $version = 'v1')
{
    return url('api/' . $version . '/' . embedParamsInRoute($route, $params));
}

function webApiUrl($route = '', array $params = [])
{
    return url('web-api/' . embedParamsInRoute($route, $params));
}

function addRdrUrl($mainUrl, $rdrUrl = null, $separatedChar = '')
{
    if (empty($rdrUrl)) {
        $rdrUrl = request()->fullUrl();
    }
    $rdrParam = AppConfig::KEY_REDIRECT_URL . '=' . urlencode($rdrUrl);
    if (!empty($separatedChar)) return $mainUrl . $separatedChar . $rdrParam;
    $parsed = parse_url($mainUrl);
    return empty($parsed['query']) ? $mainUrl . '?' . $rdrParam : $mainUrl . '&' . $rdrParam;
}

function addErrorUrl($mainUrl, $rdrUrl = null, $separatedChar = '')
{
    if (empty($rdrUrl)) {
        $rdrUrl = request()->fullUrl();
    }
    $rdrParam = AppConfig::KEY_REDIRECT_ON_ERROR_URL . '=' . urlencode($rdrUrl);
    if (!empty($separatedChar)) return $mainUrl . $separatedChar . $rdrParam;
    $parsed = parse_url($mainUrl);
    return empty($parsed['query']) ? $mainUrl . '?' . $rdrParam : $mainUrl . '&' . $rdrParam;
}

function redirectUrlAfterLogin(User $user)
{
    $localeCode = $user->settings->locale;
    $redirect_url = homeUrl();
    $overwrite_url = session()->pull(AppConfig::KEY_REDIRECT_URL);
    if (!empty($overwrite_url)) {
        $redirect_url = $overwrite_url;
    } elseif ($user->can('access-admin')) {
        $redirect_url = adminUrl(null, [], $localeCode);
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

function wrapContent($content, $before = '', $after = '', $default = '')
{
    return empty($content) ? $default : $before . $content . $after;
}

#endregion

#region App Options
function loadOptions()
{
    return AppOptionHelper::load();
}

function setOption($key, $value, $registeredBy = null)
{
    return AppOptionHelper::set($key, $value, $registeredBy);
}

function getOption($key, $default = '')
{
    return AppOptionHelper::get($key, $default);
}

#endregion

#region String
function localeInputId($name, $locale)
{
    return $name . '_' . $locale;
}

function oldLocaleInput($name, $locale, $default = null)
{
    return old(AppConfig::KEY_LOCALE_INPUT . '.' . $locale . '.' . $name, $default);
}

function localeInputName($name, $locale)
{
    return AppConfig::KEY_LOCALE_INPUT . '[' . $locale . '][' . $name . ']';
}

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
function beginsWith($haystack, $needle)
{
    return Str::startsWith($haystack, $needle);
}

/**
 * @param int $number
 * @return string
 */
function toFormattedInt($number)
{
    $helper = NumberFormatHelper::getInstance();
    $helper->modeInt();
    $number = $helper->format($number);
    $helper->modeNormal();
    return $number;
}

/**
 * @param float $number
 * @param int $mode
 * @return string
 */
function toFormattedNumber($number, $mode = NumberFormatHelper::DEFAULT_NUMBER_OF_DECIMAL_POINTS)
{
    $helper = NumberFormatHelper::getInstance();
    $helper->mode($mode);
    $number = $helper->format($number);
    $helper->modeNormal();
    return $number;
}

/**
 * @param float $number
 * @param string $originalCurrencyCode
 * @param int $mode
 * @return string
 */
function toFormattedCurrency($number, $originalCurrencyCode = null, $mode = NumberFormatHelper::DEFAULT_NUMBER_OF_DECIMAL_POINTS)
{
    $helper = NumberFormatHelper::getInstance();
    $helper->mode($mode);
    $number = $helper->formatCurrency($number, $originalCurrencyCode);
    $helper->modeNormal();
    return $number;
}

/**
 * @param string $formattedNumber
 * @return int
 */
function fromFormattedInt($formattedNumber)
{
    $helper = NumberFormatHelper::getInstance();
    $helper->modeInt();
    $number = $helper->fromFormat($formattedNumber);
    $helper->modeNormal();
    return (int)$number;
}

/**
 * @param string $formattedNumber
 * @return float
 */
function fromFormattedNumber($formattedNumber, $mode = NumberFormatHelper::DEFAULT_NUMBER_OF_DECIMAL_POINTS)
{
    $helper = NumberFormatHelper::getInstance();
    $helper->mode($mode);
    $number = $helper->fromFormat($formattedNumber);
    $helper->modeNormal();
    return $number;
}

/**
 * @param string $formattedCurrency
 * @param string $originalCurrencyCode
 * @return float
 */
function fromFormattedCurrency($formattedCurrency, $originalCurrencyCode = null, $mode = NumberFormatHelper::DEFAULT_NUMBER_OF_DECIMAL_POINTS)
{
    $helper = NumberFormatHelper::getInstance();
    $helper->mode($mode);
    $number = $helper->fromFormatCurrency($formattedCurrency);
    $helper->modeNormal();
    return $number;
}

#endregion

#region File
function randomizeFilename($prefix = null, $extension = null)
{
    return Str::format('{0}{1}_{2}{3}',
        empty($prefix) ? '' : $prefix . '_',
        time(),
        uniqid('', true),
        empty($extension) ? '' : '.' . $extension
    );
}

function makeUserPublicPath($userRelativePath)
{
    $storage = Storage::disk('file_manager');
    if (!$storage->exists($userRelativePath)) {
        $storage->makeDirectory($userRelativePath);
    }
}

function userPublicPath($userRelativePath)
{
    return public_path(concatDirectories('files', $userRelativePath));
}

function userPublicUrl($userRelativePath)
{
    return asset(urlSeparator(concatDirectories('files', $userRelativePath)));
}

function publicUrl($publicPath)
{
    return asset(urlSeparator(str_replace(public_path() . DIRECTORY_SEPARATOR, '', $publicPath)));
}

function urlSeparator($path)
{
    return str_replace('\\', '/', $path);
}

function dirSeparator($path)
{
    return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}

function containBackDirectory($path)
{
    return Str::startsWith('..\\', $path)
        || Str::contains('\\..\\', $path)
        || Str::startsWith('../', $path)
        || Str::contains('/../', $path);
}

function concatDirectories()
{
    $args = func_get_args();
    return implode(DIRECTORY_SEPARATOR, $args);
}

/**
 * @return int The maximum size of an uploaded file in bytes
 */
function maxUploadFileSize()
{
    return UploadedFile::getMaxFilesize();
}

/**
 * @param int $fileSize File size in bytes
 * @return string
 */
function asByte($fileSize)
{
    return $fileSize . ' byte' . ($fileSize > 1 ? 's' : '');
}

/**
 * @param int $fileSize File size in bytes
 * @return string
 */
function asKb($fileSize)
{
    return round($fileSize / 1024) . 'KB';
}

/**
 * @param int $fileSize File size in bytes
 * @return string
 */
function asMb($fileSize)
{
    return round($fileSize / 1024 / 1024) . 'MB';
}

#endregion

#region Utilities
function isEmptyArray($array)
{
    foreach ((array)$array as $item) {
        if (is_array($item)) {
            $empty = isEmptyArray($item);
        } else {
            $empty = empty($item);
        }
        if (!$empty) return false;
    }
    return true;
}

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
function content_filter($id, $content, array $params = [])
{
    return ContentFilter::flush($id, $content, $params);
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

function placeholder($name, $before = '', $after = '', $default = '')
{
    return WidgetsFacade::display($name, $before, $after, $default);
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
    return in_admin() ?
        AdminThemeFacade::title($titles, $use_root, $separator)
        : HomeThemeFacade::title($titles, $use_root, $separator);
}

function theme_description($description = '')
{
    return in_admin() ?
        AdminThemeFacade::description($description)
        : HomeThemeFacade::description($description);
}

function theme_author($author = '')
{
    return in_admin() ?
        AdminThemeFacade::author($author)
        : HomeThemeFacade::author($author);
}

function theme_application_name($applicationName = '')
{
    return in_admin() ?
        AdminThemeFacade::applicationName($applicationName)
        : HomeThemeFacade::applicationName($applicationName);
}

function theme_generator($generator = '')
{
    return in_admin() ?
        AdminThemeFacade::generator($generator)
        : HomeThemeFacade::generator($generator);
}

function theme_keywords($keywords = '')
{
    return in_admin() ?
        AdminThemeFacade::keywords($keywords)
        : HomeThemeFacade::keywords($keywords);
}

function lib_styles()
{
    return in_admin() ? AdminThemeFacade::getLibCss() : HomeThemeFacade::getLibCss();
}

function ext_styles()
{
    return in_admin() ? AdminThemeFacade::getExtCss() : HomeThemeFacade::getExtCss();
}

function lib_scripts()
{
    return in_admin() ? AdminThemeFacade::getLibJs() : HomeThemeFacade::getLibJs();
}

function ext_scripts()
{
    return in_admin() ? AdminThemeFacade::getExtJs() : HomeThemeFacade::getExtJs();
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

function in_admin()
{
    return Theme::$isAdmin;
}

#endregion

#region Runtime
/**
 * @return int Session timeout in milliseconds
 */
function sessionLifetime()
{
    return intval(config('session.lifetime')) * 60 * 1000;
}

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
    return 'Laravel v' . \Illuminate\Foundation\Application::VERSION;
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

/**
 * @return Katniss\Everdeen\Utils\Settings
 */
function settings()
{
    return app('settings');
}

function allGenders()
{
    return config('katniss.genders');
}

function allCountries()
{
    return config('katniss.countries');
}

function allCountryCodes()
{
    return array_keys(allCountries());
}

function allCountry($code, $property = '')
{
    $countries = allCountries();
    if (empty($countries[$code])) return null;
    return empty($property) || $property == 'all' ? $countries[$code] : $countries[$code][$property];
}

function countriesAsOptions($selected_country = 'VN')
{
    $options = '';
    foreach (allCountries() as $code => $properties) {
        $options .= '<option value="' . $code . '"' . ($selected_country == $code ? ' selected' : '') . '>' . $properties['name'] . '</option>';
    }
    return $options;
}

function callingCodesAsOptions($selected_country = 'VN')
{
    $options = '';
    foreach (allCountries() as $code => $properties) {
        $options .= '<option value="' . $code . '"' . ($selected_country == $code ? ' selected' : '') . '>(+' . $properties['calling_code'] . ') ' . $properties['name'] . '</option>';
    }
    return $options;
}

function allCurrencies()
{
    return config('katniss.currencies');
}

function allCurrencyCodes()
{
    return array_keys(allCurrencies());
}

function allCurrency($code, $property = '')
{
    $currencies = allCurrencies();
    if (empty($currencies[$code])) return null;
    return empty($property) || $property == 'all' ? $currencies[$code] : $currencies[$code][$property];
}

function currenciesAsOptions($selected_currency = 'VND')
{
    $options = '';
    foreach (allCurrencies() as $code => $properties) {
        $options .= '<option value="' . $code . '"' . ($selected_currency == $code ? ' selected' : '') . '>' . $properties['name'] . ' (' . $properties['symbol'] . ')' . '</option>';
    }
    return $options;
}

function allNumberFormats()
{
    return config('katniss.number_formats');
}

function numberFormatsAsOptions($selected_number_format = 'point-comma')
{
    $options = '';
    foreach (allNumberFormats() as $number_format) {
        $options .= '<option value="' . $number_format . '"' . ($selected_number_format == $number_format ? ' selected' : '') . '>' . NumberFormatHelper::doFormat(12345.67, $number_format) . '</option>';
    }
    return $options;
}

function timeZoneListAsOptions($selected)
{
    return DateTimeHelper::getTimeZoneListAsOptions($selected);
}

function daysOfWeekAsOptions($selected)
{
    return DateTimeHelper::getDaysOfWeekAsOptions($selected);
}

function longDateFormatsAsOptions($selected)
{
    return DateTimeHelper::getLongDateFormatsAsOptions($selected);
}

function shortDateFormatsAsOptions($selected)
{
    return DateTimeHelper::getShortDateFormatsAsOptions($selected);
}

function longTimeFormatsAsOptions($selected)
{
    return DateTimeHelper::getLongTimeFormatsAsOptions($selected);
}

function shortTimeFormatsAsOptions($selected)
{
    return DateTimeHelper::getShortTimeFormatsAsOptions($selected);
}
#endregion