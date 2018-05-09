<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/8
 * Time: 15:49
 */

namespace zero\base;


class Controller {

    protected $controller;
    protected $action;

    public function __construct($controller, $action) {
        $this->controller = $controller;
        $this->action     = $action;
        echo "this is construct method of controller<br>";
        echo $this->controller;
        echo "<br>done<br>";
    }
}