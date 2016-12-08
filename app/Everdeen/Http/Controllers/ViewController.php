<?php

namespace Katniss\Everdeen\Http\Controllers;

use Katniss\Everdeen\Http\Request;
use Katniss\Everdeen\Utils\AppConfig;
use Katniss\Everdeen\Utils\DataStructure\Pagination\PaginationRender;

class ViewController extends KatnissController
{
    /**
     * @var string
     */
    protected $viewPath;

    protected $paginationRender;

    public function __construct()
    {
        parent::__construct();

        $this->viewPath = '';
        $this->paginationRender = new PaginationRender();
    }

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
        $params['code'] = $code;
        $headers = [];
        if (isset($params['headers'])) {
            $headers = (array)$params['headers'];
            unset($params['headers']);
        }
        if (!isset($params['message'])) {
            $params['message'] = trans('error.unknown');
        }

        $view = $request->theme()->resolveErrorView($code, $params['original_path']);
        if ($view !== false) {
            return response()->view($view, $params, $code, $headers);
        }
        return '';
    }
}
