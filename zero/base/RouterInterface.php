<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/10
 * Time: 11:27
 */

namespace zero\base;

interface RouterInterface {

    public static function parser($url, $request_method);

    public static function urlGenerator($route, $param = null);

}