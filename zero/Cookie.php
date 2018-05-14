<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/13
 * Time: 21:45
 */

namespace zero;


class Cookie {

    private $cookies_array = [];
    private $cookies_update_array = [];

    /**
     * Initialize cookie
     * Cookie constructor.
     */
    public function __construct() {
        foreach ($_COOKIE as $name => $value) {
            $this->cookies_array[$name] = $value;
        }
    }

    /**
     * Get the cookie by name.
     * @param string $name
     * @return string|null the value of the cookie. If the cookie doesn't exist, it will return null.
     */
    public function get($name) {
        if (isset($this->cookies_array[$name])) {
            return $this->cookies_array[$name];
        } elseif(isset($this->cookies_update_array[$name]) && $this->cookies_update_array[$name]['delete'] == false) {
            return $this->cookies_update_array[$name];
        }
        return null;
    }

    /**
     * Setup a cookie. If the cookie is exist, it will get updated automatically.
     * @param string $name the name of cookie item.
     * @param string $value the value of cookie item.
     * @param int $expiration the time remaining in seconds before the cookie expires. If not set, cookies will get
     * delete when browser is closed.
     */
    public function set($name, $value, $expiration = 0, $path = "", $domain= "", $secure = false,
                        $httponly = false) {
        if ($expiration === 0) {
            $this->cookies_update_array[$name] = ['value' => $value, 'expiration' => 0,
                'delete' => false, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly];
        } else {
            $this->cookies_update_array[$name] = ['value' => $value, 'expiration' => time() + $expiration,
                'delete' => false, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly];
        }
    }

    public function has($name) {
        if (isset($this->cookies_array[$name])) {
            return true;
        }
        return false;
    }

    /**
     * Delete a cookie by name.
     * This will set the expire time before now.
     * @param string $name the cookie's name that you want to delete.
     */
    public function delete($name) {
        if ($this->has($name)) {
            $this->set($name, null, 0);
            unset($this->cookies_array[$name]);     // remove the cookie from cookie array
        }
    }

    public function getAll() {
        return $this->cookies_array;
    }

    public function getAllUpdates() {
        return $this->cookies_update_array;
    }
}