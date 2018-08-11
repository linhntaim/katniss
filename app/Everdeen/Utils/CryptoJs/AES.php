<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2017-12-27
 * Time: 16:20
 */

namespace Katniss\Everdeen\Utils\CryptoJs;


class AES
{
    public static function encrypt($data, $passPhrase, $salt = null)
    {
        $salt = $salt ?: openssl_random_pseudo_bytes(8);
        list($key, $iv) = self::evpkdf($passPhrase, $salt);
        $ct = openssl_encrypt($data, 'aes-256-cbc', $key, true, $iv);
        return self::encode($ct, $salt);
    }

    public static function decrypt($base64, $passphrase)
    {
        list($ct, $salt) = self::decode($base64);
        list($key, $iv) = self::evpkdf($passphrase, $salt);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return $data;
    }

    public static function evpkdf($passPhrase, $salt)
    {
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $passPhrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        return [$key, $iv];
    }

    public static function decode($base64)
    {
        $data = base64_decode($base64);
        if (substr($data, 0, 8) !== 'Salted__') {
            throw new \InvalidArgumentException();
        }
        $salt = substr($data, 8, 8);
        $ct = substr($data, 16);
        return [$ct, $salt];
    }

    public static function encode($ct, $salt)
    {
        return base64_encode('Salted__' . $salt . $ct);
    }
}