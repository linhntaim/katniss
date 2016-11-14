<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-21
 * Time: 03:50
 */

namespace Katniss\Everdeen\Utils\ORTC;


class Realtime
{
    private $ortc_data;
    private $is_version_21;

    function __construct($balancer, $app_key, $priv_key, $token)
    {

        $arg_list = func_get_args();
        if (count($arg_list) < 4) die("ERROR: Some of constructor's parameters are missing.");
        foreach ($arg_list as $idx => $val)
            if (trim($val) == "")
                die("ERROR: parameters '$idx' is not specified.");

        $strpos_res = strpos($balancer, "2.1");
        $this->is_version_21 = ($strpos_res > 0);

        $this->ortc_data['balancer'] = $balancer;
        $this->ortc_data['app_key'] = $app_key;
        $this->ortc_data['priv_key'] = $priv_key;
        $this->ortc_data['token'] = $token;
    }


    private function _get_server()
    {
        $url = $this->ortc_data['balancer'] . '?appkey=' . $this->ortc_data['app_key'];
        $balancer_response = Request::execute("GET", $url);
        if ($balancer_response['errcode'] != 0) {
            die('Error getting data from balancer! ' . $balancer_response['error'] . ' Response: ' . print_r($balancer_response['response'], true));
        }
        // error
        if (!preg_match('/https?:\/\/[^\"]+/', $balancer_response['content'], $matches)) {
            return '';
        }
        if ('http://undefined:undefined' == $matches[0]) return '';

        // success
        return $matches[0];
    }

    private function send_message_part($url, $channel, $msg, &$response = Array())
    {
        $message = array(
            'AK' => $this->ortc_data['app_key'],
            'PK' => $this->ortc_data['priv_key'],
            'AT' => $this->ortc_data['token'],
            'C' => $channel,
            'M' => $msg
        );

        $content = Request::execute("POST", $url . '/send/', $message);

        $response = $content;

        return ($content['errcode'] == 0);
    }

    public function send($channel, $msg, &$response = Array())
    {

        $url = $this->_get_server();

        if (!$url || $url == "") return false; // no server available

        $numberOfParts = ((int)(strlen($msg) / 700)) + ((strlen($msg) % 700 == 0) ? 0 : 1);
        $guid = substr(uniqid(), 5, 8);

        if ($numberOfParts > 1) {
            $part = 1;


            while ($part <= $numberOfParts) {

                $ret = $this->send_message_part($url, $channel, $guid . "_" . $part . "-" . $numberOfParts . "_" . substr($msg, ($part - 1) * 699, 699), $response); // $response returned used for debug purposes
                if (!$ret) return false;

                $part = $part + 1;

            }

            return true;
        } else {
            $ret = $this->send_message_part($url, $channel, $guid . "_1-1_" . $msg, $response); // returning $response for debug purpose
            return $ret;
        }
    }


    public function auth($channels, $private = 0, $expire_time = 180000, &$response = Array())
    {

        // post permissions
        $fields = array(
            'AK' => $this->ortc_data['app_key'],
            'PK' => $this->ortc_data['priv_key'],
            'AT' => $this->ortc_data['token'], //access token
            'PVT' => $private,
            'TTL' => $expire_time,
            'TP' => count($channels) // total num of channels
        );

        foreach ($channels as $channel => $perms) {
            $fields[$channel] = $perms;
        }
        $url = $this->_get_server();
        if (!$url) return false; // no server available

        $auth_path = '/authenticate';

        $content = Request::execute('POST', $url . $auth_path, $fields, $referer = '', 15, 'ortc-php'); // /auth or /authenticate depends on the server version

        $response = $content;

        return ($content['errcode'] == 0);
    }

}
