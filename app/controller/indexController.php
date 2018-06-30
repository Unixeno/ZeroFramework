<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 23:40
 */

namespace app\controller;
use zero\base\Controller;
use zero\Request;
use zero\Respond;

class indexController extends Controller{
    public function index() {
        $this->assign('title', 123);
        $this->assign('content', $this->router::urlGenerator('',
            [
                'a' => 'a',
                'gf' => 'b',
                '#' => '123'
            ]));
//        Respond::headers()->set('access-control-allow-origin', 'https://yangwang.hk/');
//        Respond::cookie()->set('aha', 'value');
        $this->display();
    }

    public function show404() {
        Respond::setResponseCode(404);
        $this->display('404');
    }

    public function getcookie() {
        Respond::cookie()->set('aha', 'value', 86400);
        Respond::respond();
    }

    public function deletecookie() {
        Respond::cookie()->delete('aha');
        Respond::respond();
    }

    public function index2() {
        $this->assign('title', "Good Bye Human Beings！");
        $this->display('index');
    }
}