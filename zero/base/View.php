<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/9
 * Time: 20:04
 */

namespace zero\base;

use zero\Config;
use zero\debug\Trace;

class View {

    protected $controller;
    protected $action;

    protected $loader;
    protected $twig;
    protected $assigned_list = [];

    public function __construct($controller, $action) {
        $this->controller = $controller;
        $this->action     = $action;

        $this->loader = new \Twig_Loader_Filesystem(APP_ROOT.'/app/view/'.$controller);
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => APP_ROOT.'/runtime/template_cache',
            'auto_reload' => APP_DEBUG
        ));
    }

    public function assign($key, $value) {
        $this->assigned_list[$key] = $value;
    }

    public function render($template_file = null) {
        if (DEBUG_TRACE) {
            Trace::tick('Start rendering');
        }

        if ($template_file == NULL) {
            $template_file = $this->action;
        }

        $content = $this->twig->render($template_file.'.html', $this->assigned_list);

        if (DEBUG_TRACE) {
            Trace::tick('Rendering finished');
        }

        return $content;
    }
}