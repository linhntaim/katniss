<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-26
 * Time: 20:22
 */

namespace Katniss\Everdeen\Http\Controllers;


use Katniss\Everdeen\Exceptions\KatnissException;

trait ApiResponseTrait
{
    protected static $extraResponse = [
        '_block' => null,
    ];

    public static function addBlockResponseMessage($message, $fresh = false)
    {
        if ($fresh || self::$extraResponse['_block'] == null) {
            self::$extraResponse['_block'] = [];
        }
        self::$extraResponse['_block'][] = $message;
    }

    /**
     * @param boolean $failed
     * @param array|null $data
     * @param array|string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($failed, $data = null, $message = '')
    {
        return response()->json([
            '_success' => !$failed,
            '_messages' => empty($message) ? null : (array)$message,
            '_data' => $data,
            '_extra' => self::$extraResponse,
        ]);
    }

    /**
     * @param array|null $data
     * @param array|string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccess($data = null, $message = '')
    {
        $this->transactionComplete();
        return $this->response(false, $data, $message);
    }

    /**
     * @param \Exception|array|string $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseFail($message = '', $data = null)
    {
        $this->transactionStop();
        if ($message instanceof KatnissException) {
            $exception = $message;
            $message = $exception->getMessage();
            $data = array_merge((array)$data, [
                'attached' => $exception->getAttachedData(),
                'exception' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);
            logError($exception);
        } elseif ($message instanceof \Exception) {
            $exception = $message;
            $message = $exception->getMessage();
            $data = array_merge((array)$data, [
                'exception' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);
            logError($exception);
        }
        return $this->response(true, $data, $message);
    }
}
