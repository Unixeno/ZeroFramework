<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/8
 * Time: 21:26
 */

namespace zero\base;

use Medoo\Medoo;
use zero\Config;

class Model extends Medoo{
    protected $model;
    public function __construct() {
        $options = array('database_type' => Config::get('DATABASE_TYPE'),
            'database_name' => Config::get('DATABASE_NAME'),
            'server'        => Config::get('DATABASE_SERVER'),
            'username'      => Config::get('DATABASE_USER'),
            'password'      => Config::get('DATABASE_PASSWORD'));
        parent::__construct($options);

        $this->model = get_class($this);
    }
}