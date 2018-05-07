<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 17:30
 */

namespace Zero\Debug;


class DebugCore {
    public function __construct($level) {

    }

    public function errorHandler($errno, $errstr, $errfile, $errline) {
        echo '<h2>It must be something wrong! :(</h2>';
        return false;
    }
}