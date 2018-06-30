<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/8
 * Time: 15:49
 */

namespace zero\base;

use zero\base\View;
use zero\Respond;

class Controller {

    protected $controller;
    protected $action;
    protected $view;
    protected $router;

    public function __construct($controller, $action, $router) {
        $this->controller = $controller;
        $this->action     = $action;
        $this->router     = $router;

        $this->view = new View($controller, $action);
    }

    public function assign($key, $value) {
        $this->view->assign($key, $value);
    }

    public function display($template_file = null) {
        Respond::$content = $this->view->render($template_file);
        Respond::respond();
    }

    public function render($template_file = null) {
        return $this->view->render($template_file);
    }
}