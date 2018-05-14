<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/9
 * Time: 13:56
 */

namespace zero;


class Request {

    private static $filter_method;
    private static $cookie;

    public static function get($key) {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        } else {
            return '';
        }
    }

    public static function post($key) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            return '';
        }
    }

    public static function cookie() {
        if (isset(self::$cookie)) {
            return self::$cookie;
        }
        else {
            self::$cookie = new Cookie();
            return self::$cookie;
        }
    }

    public static function requestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function requestURL() {
        return explode('?', $_SERVER["REQUEST_URI"])[0];
    }

    public static function filter($str) {
        return $str;
    }
}