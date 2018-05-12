<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/10
 * Time: 23:42
 */

namespace zero;


class Header {
    protected $header_array = [];

    public function set($name, $value) {
        $this->header_array[$name] = $value;
    }

    public function remove($name) {
        if (isset($this->header_array[$name])) {
            unset($this->header_array[$name]);
        }
    }

    public function get($name) {
        if (isset($this->header_array[$name])) {
            return $this->header_array[$name];
        }
    }

    public function getAll() {
        return $this->header_array;
    }
}