<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/10
 * Time: 12:27
 */

namespace zero\router;

use zero\base\RouterInterface;
use zero\Config;
use zero\Request;

class BasicRouter implements RouterInterface{

    public static function parser($url, $request_method) {
        // TODO: Implement parser() method.
        $routing = array(
            'application' => Config::get('ROUTE_DEFAULT_APPLICATION'),
            'controller'  => Config::get('ROUTE_DEFAULT_CONTROLLER'),
            'action'      => Config::get('ROUTE_DEFAULT_ACTION')
        );
        if (isset($_GET['m']))
            $routing['application'] = Request::get('m');
        if (isset($_GET['c']))
            $routing['controller'] = Request::get('c');
        if (isset($_GET['a']))
            $routing['action'] = Request::get('a');

        return $routing;
    }

    public static function urlGenerator($route, $param) {
        // TODO: Implement urlGenerator() method.
    }

}