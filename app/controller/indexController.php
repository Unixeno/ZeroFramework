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
//        echo '<h1>Hello world!</h1>';
//        echo '<p>This message came from index controller!</p>';

        $m = new indexModel();

        $this->assign('var', date("Y-m-d h:i:sa", time()));
        $this->render();
    }
}