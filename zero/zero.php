<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 16:11
 */

namespace zero;

use Twig\Extension\CoreExtension;
use zero\debug\DebugCore;
use zero\debug\Trace;
use zero\Config;

class Bootstrap {
    private $trace;
    private $config;
    private $debug;
    public function __construct($config = array()) {

        defined('APP_DEBUG') or define('APP_DEBUG', false);
        defined('CORE_PATH') or define('CORE_PATH', __DIR__);
        defined('APP_PATH')  or define('APP_PATH', APP_ROOT.'/app');
        defined('DEBUG_TRACE') or define('DEBUG_TRACE', false);

        spl_autoload_register('zero\Bootstrap::autoLoad');  //register the auto load function

        // setup debug module
        require_once (CORE_PATH.'/debug/DebugCore.php');
        set_error_handler('zero\\debug\\DebugCore::errorHandler', E_ALL);
        set_exception_handler('zero\\debug\\DebugCore::exceptionHandler');
        //load composer requirements
        require APP_ROOT.'/vendor/autoload.php';
    }

    public function run() {

        if (DEBUG_TRACE)
            require CORE_PATH.'/debug/Trace.php';
        Trace::start();

        $this->config = require CORE_PATH.'/default.configure.php';
        //array_merge($this->config, $config);
        Config::setArray($this->config);

        $this->route();

        if (DEBUG_TRACE) {
            Trace::tick('finished');
            echo (Trace::printTime());
        }
    }

    public function route() {
        if (DEBUG_TRACE)
            Trace::tick('routing');

        $router = 'zero\\router\\'.Config::get('ROUTER').'Router';
        if (!class_exists($router)) {
            $router = 'app\\router\\'.Config::get('ROUTER').'Router';
        }


        $routing = $router::parser(1, Request::requestMethod());

        if ($this->config['SINGLE_APPLICATION']) {
            $controller = 'app\\controller\\'.$routing['controller'].'Controller';
        } else {
            $controller = 'app\\'.$routing['application'].'\\controller\\'.$routing['controller'].'Controller';
        }
        if (!class_exists($controller)) {
            trigger_error('Controller '.$controller.' not found', E_USER_ERROR);
        } else if (!method_exists($controller, $routing['action'])){
            trigger_error('Method not exist in Controller'.$controller, E_USER_ERROR);
        }

        $dispatch = new $controller($routing['controller'], $routing['action']);
        call_user_func_array(array($dispatch, $routing['action']), array("one"));
        DebugCore::errorPrinter();
    }

    public static function autoLoad($class_name) {
        if (DEBUG_TRACE)
            Trace::tick('load class: '.$class_name);
        $default_class_map = array(
            'zero\base\Controller'  => CORE_PATH.'/base/Controller.php',
            'zero\base\Model'       => CORE_PATH.'/base/Model.php',
            'zero\debug\Trace'      => CORE_PATH.'/debug/Trace.php',
            'zero\Config'           => CORE_PATH.'/Config.php',
            'zero\BasicRouter'      => CORE_PATH.'/router/BasicRouter.php'
        );
        
        if (isset($default_class_map[$class_name])) {
            $file_path = $default_class_map[$class_name];
        } else {
            $file_path = APP_ROOT.'/'.str_replace('\\', '/', $class_name).'.php';
        }
        if (!is_file($file_path)) {
            return;
        } else {
            include $file_path;
        }
    }
}
