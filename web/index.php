<?php

define('APP_DEBUG', true);
define('DEBUG_TRACE', true);
define('APP_ROOT', __DIR__.'/..');

require_once(APP_ROOT . '/zero/zero.php');

(new Zero\Bootstrap())->run();
