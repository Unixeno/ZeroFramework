<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/8
 * Time: 15:49
 */

namespace zero\base;

use zero\base\View;

class Controller {

    protected $controller;
    protected $action;
    protected $view;

    public function __construct($controller, $action) {
        $this->controller = $controller;
        $this->action     = $action;

        $this->view = new View($controller, $action);
    }

    public function assign($key, $value) {
        $this->view->assign($key, $value);
    }

    public function render() {
        $this->view->render();
    }
}