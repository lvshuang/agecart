<?php

namespace Top\Component\Curl;

class Curl {

    protected static $timeout = 5;
    protected static $connectTimeout = 30;
    protected static $userAgent;
    protected static $method;

    public static function get($url, $params = array()) 
    {
        self::$method = "GET";
        $queryString = http_build_query($params);
        return self::request($url . '?' . $queryString);
    }

    public static function post($url, $params = array())
    {
        self::$method = 'POST';
        return self::result($url, $params);
    }

    protected static function request($url, $params = array())
    {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (self::$method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        } 
        $result = curl_exec($ch);

        return $result;
    }

}