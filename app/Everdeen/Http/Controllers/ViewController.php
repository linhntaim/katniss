<?php

namespace Katniss\Everdeen\Http\Controllers;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\AppConfig;

class ViewController extends KatnissController
{
    /**
     * @var string
     */
    protected $viewPath;

    #region Theme
    /**
     * Get global view params
     *
     * @return array
     */
    protected function _params()
    {
        return $this->currentRequest->theme()->viewParams();
    }

    protected function _title($title, $use_root = true, $separator = '&raquo;')
    {
        return $this->currentRequest->theme()->title($title, $use_root, $separator);
    }

    protected function _description($description = '')
    {
        return $this->currentRequest->theme()->description($description);
    }

    protected function _error($name)
    {
        return $this->currentRequest->theme()->error($name);
    }

    protected function _errorExists($view)
    {
        return view()->exists($this->_error($view));
    }

    protected function _err($code, $view, $data = [], $headers = [])
    {
        return $this->_e($code, $this->_error($view), $data, $headers);
    }

    protected function _e($code, $view, $data = [], $headers = [])
    {
        return response()->view($view, $data, $code, $headers);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function _page($name)
    {
        return $this->currentRequest->theme()->page($name);
    }

    protected function _pageExists($view)
    {
        return view()->exists($this->_page($this->viewPath . '.' . $view));
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _view($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath), $data, $mergeData);
    }

    /**
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _any($view, $data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.' . $view), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _index($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.index'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _create($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.create'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _edit($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.edit'), $data, $mergeData);
    }

    /**
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function _show($data = [], $mergeData = [])
    {
        return view($this->_page($this->viewPath . '.show'), $data, $mergeData);
    }

    #endregion

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

    public function error(Request $request, $code)
    {
        $params = $request->all();
        $headers = [];
        if (isset($params['headers'])) {
            $headers = (array)$params['headers'];
            unset($params['headers']);
        }
        if ($this->_errorExists($code)) {
            return $this->_err($code, $code, $params, $headers);
        } elseif ($this->_errorExists('common')) {
            return $this->_err($code, 'common', $params, $headers);
        } elseif (view()->exists('errors.' . $code)) {
            return $this->_e($code, 'errors' . $code, $params, $headers);
        } elseif (view()->exists('errors.common')) {
            return $this->_e($code, 'errors.common', $params, $headers);
        }
        return '';
    }
}
