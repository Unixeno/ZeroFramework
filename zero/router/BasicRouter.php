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
    static $routing;

    public static function parser($url, $request_method) {
        // TODO: Implement parser() method.
        self::$routing = array(
            'application' => Config::get('ROUTE_DEFAULT_APPLICATION'),
            'controller'  => Config::get('ROUTE_DEFAULT_CONTROLLER'),
            'action'      => Config::get('ROUTE_DEFAULT_ACTION')
        );
        if (isset($_GET['m']))
            self::$routing['application'] = Request::get('m');
        if (isset($_GET['c']))
            self::$routing['controller'] = Request::get('c');
        if (isset($_GET['a']))
            self::$routing['action'] = Request::get('a');

        return self::$routing;
    }

    /***
     * The url generator method which create and return a url based on the given router.
     * When the route param is an empty string, this method will return the url to current module
     * If the first character of the route parameter is '@', the parameter will be seen as a direct url.
     * @param $route string
     * @param $param array
     * @return string
     */
    public static function urlGenerator($route, $param = []) {
        $final_url = '';
        if (substr($route, 0, 1) === '@'){
            $final_url = substr($route, 1).static::paramGenerator($param);
        } else {
            if (substr($route, 0, 1) === '\\') {
                $route = static::absoluteGenerator($route);
                $whole_param = $route + $param;
                $final_url = Request::requestFile().static::paramGenerator($whole_param);
            } else {
                $route = static::relativeGenerator($route);
                $whole_param = $route + $param;
                $final_url = Request::requestFile().static::paramGenerator($whole_param);
            }
        }
        return $final_url;
    }

    private static  function absoluteGenerator($route) {
        $param = [];
        $component = explode('/', $route);
        if (empty($route)) {
            $param['m'] = self::$routing['application'];
            $param['c'] = self::$routing['controller'];
            $param['a'] = self::$routing['action'];
        }elseif(count($component) === 1) {
            $param['m'] = self::$routing['application'];
            $param['c'] = self::$routing['controller'];
            $param['a'] = $component[0];
        } elseif (count($component) === 2) {
            $param['m'] = self::$routing['application'];
            $param['c'] = $component[0];
            $param['a'] = $component[1];
        } elseif (count($component) === 3) {
            $param['m'] = $component[0];
            $param['c'] = $component[1];
            $param['a'] = $component[2];
        }
        return $param;
    }

    private static function relativeGenerator($route) {
        $param = [];
        $component = explode('/', substr($route, 1));
        if (empty($route)) {
            $param['m'] = Config::get('ROUTE_DEFAULT_APPLICATION');
            $param['c'] = Config::get('ROUTE_DEFAULT_CONTROLLER');
            $param['a'] = Config::get('ROUTE_DEFAULT_ACTION');
        }elseif(count($component) === 1) {
            $param['m'] = Config::get('ROUTE_DEFAULT_APPLICATION');
            $param['c'] = Config::get('ROUTE_DEFAULT_CONTROLLER');
            $param['a'] = $component[0];
        } elseif (count($component) === 2) {
            $param['m'] = Config::get('ROUTE_DEFAULT_APPLICATION');
            $param['c'] = $component[0];
            $param['a'] = $component[1];
        } elseif (count($component) === 3) {
            $param['m'] = $component[0];
            $param['c'] = $component[1];
            $param['a'] = $component[2];
        }
        return $param;
    }

    private static function paramGenerator($params) {
        $params_list = [];
        $tag_string = '';
        if ($params['m'] === Config::get('ROUTE_DEFAULT_APPLICATION')) {
            unset($params['m']);
        }
        if ($params['c'] === Config::get('ROUTE_DEFAULT_CONTROLLER')) {
            unset($params['c']);
        }
        if ($params['a'] === Config::get('ROUTE_DEFAULT_ACTION')) {
            unset($params['a']);
        }
        foreach ($params as $param => $value) {
            if ($param === '#') {
                $tag_string = '#' . $value;
                continue;
            } else {
                array_push($params_list, $param.'='.$value);
            }
        }
        $params_string = implode('&', $params_list);
        if (!empty($params_string)) {
            $params_string = '?'.$params_string;
        }

        return $params_string.$tag_string;
    }

}