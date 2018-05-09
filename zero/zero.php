<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 16:11
 */

namespace zero;

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

        $routing = array(
            'application' => $this->config['ROUTE_DEFAULT_APPLICATION'],
            'controller'  => $this->config['ROUTE_DEFAULT_CONTROLLER'],
            'action'      => $this->config['ROUTE_DEFAULT_ACTION']
        );

        if ($this->config['URL_MODE'] == 1) {
            if (isset($_GET['a']))
                $routing['application'] = $_GET['a'];
            if (isset($_GET['c']))
                $routing['controller'] = $_GET['c'];
            if (isset($_GET['r']))
                $routing['controller'] = $_GET['r'];
        } else {
            //to do
        }
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
            'zero\Config'           => CORE_PATH.'/Config.php'
        );
        
        if (isset($default_class_map[$class_name])) {
            $file_path = $default_class_map[$class_name];
        } else {
            $file_path = APP_ROOT.'/'.str_replace('\\', '/', $class_name).'.php';
        }
        if (!is_file($file_path)) {
            trigger_error('Can\'t find class: '.$class_name.' in '.$file_path, E_USER_ERROR);
            return;
        } else {
            include $file_path;
        }
    }
}
