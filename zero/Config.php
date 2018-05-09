<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/9
 * Time: 10:34
 */

namespace zero;


class Config {
    private static $config = [];

    public static function get($name) {
        if (isset(self::$config[$name])) {
            return self::$config[$name];
        }
    }

    public static function set($name, $value) {
        self::$config[$name] = $value;
    }

    public static function setArray($array) {
        foreach ($array as $name => $value) {
            self::$config[$name] = $value;
        }
    }
}