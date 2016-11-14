<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-21
 * Time: 03:51
 */

namespace Katniss\Everdeen\Utils\ORTC;


class Request
{
    static function execute($method, $url, $data = array(), $referer = '', $timeout = 30, $user_agent = '')
    {
        // Convert the data array into URL Parameters like a=b&foo=bar etc.
        $data = http_build_query($data);
        // parse the given URL
        $url = parse_url($url);
        // extract host and path
        $host = $url['host'];
        $path = isset($url['path']) ? $url['path'] : '';

        if (trim($host) == "") {
            return array(
                'errcode' => -2,
                'status' => 'err',
                'error' => "Error: (parse_url) Host is empty"
            );
        }

        if ($url['scheme'] == 'http') {
            $port = 80;
            $protocol = '';
        } else {
            $port = 443;
            $protocol = 'ssl://';
        }
        // open a socket connection - timeout: 30 sec
        $fp = fsockopen($protocol . $host, $port, $errno, $errstr, $timeout);
        if ($fp) {
            // send the request headers:
            fputs($fp, "$method $path HTTP/1.1\r\n");
            fputs($fp, "host: $host\r\n");
            if ($referer != '') fputs($fp, "Referer: $referer\r\n");
            if ($method == 'POST') {
                fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
                fputs($fp, "Content-length: " . strlen($data) . "\r\n");
            }
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $data);
            $result = '';
            while (!feof($fp)) {
                // receive the results of the request
                $result .= fgets($fp, 128);
            }
        } else {
            return array(
                'errcode' => -3,
                'status' => 'err',
                'error' => "$errstr ($errno)"
            );
        }
        // close the socket connection:
        fclose($fp);

        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);

        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';

        // be carefull with HTTP1.1 (nginx)
        if (preg_match('/Transfer-Encoding: chunked/i', $header)) {
            $chunks = explode("\n", $content);
            if (count($chunks) > 1) {
                foreach ($chunks as $line => $data) {
                    if ($line % 2 == 0) unset($chunks[$line]);

                }
                array_pop($chunks);
                $content = implode("\n", $chunks);
            } else
                $content = $result[1];
        }
        if (!preg_match('/^HTTP\/1.1 ([0-9]{3})/', $header, $matches)) {

            return array(
                'errcode' => -4,
                'status' => 'err',
                'error' => 'Error: failed to localize http 1.1 in the result header',
                'response' => $result
            );
        };

        if (!$matches[1] || $matches[1][0] !== '2') {
            return array(
                'errcode' => -5,
                'status' => 'err',
                'error' => "Error: response was not successful",
                'response' => $result
            );
        }

        // return as structured array:
        return array(
            'errcode' => 0,
            'status' => 'ok',
            'header' => $header,
            'content' => $content
        );
    }

}
