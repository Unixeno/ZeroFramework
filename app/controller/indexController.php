<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 23:40
 */

namespace app\controller;
use zero\base\Controller;
use app\model\indexModel;

class indexController extends Controller{
    public function index() {
        $this->assign('title', "Hello World!");
        $this->assign('var', date("Y-m-d h:i:sa", time()));
        $this->render();
    }

    public function index2() {
        $this->assign('title', "Good Bye Human Beings！");
        $this->assign('var', date("Y-m-d h:i:sa", time()));
        $this->render('index');
    }
}