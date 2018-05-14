<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/10
 * Time: 18:03
 */

namespace zero;

use http\Exception;

class Respond {
    private static $is_sent = false;
    private static $status_code = 200;
    private static $status_text = 'OK';
    private static $header;
    private static $cookie;

    private static $http_statuses = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        118 => 'Connection timed out',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        210 => 'Content Different',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway or Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        507 => 'Insufficient storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    public static $content = "";

    public static function headers() {
        if (isset(self::$header)) {
            return self::$header;
        } else {
            self::$header = new Header();
            return self::$header;
        }
    }

    public static function cookie() {
        if (isset(self::$cookie)) {
            return self::$cookie;
        } else {
            self::$cookie = Request::cookie();
            return self::$cookie;
        }
    }

    public static function jsonRespond() {

    }

    public static function fileRespond($filename) {

    }


    /**
     * Set the HTTP status code when respond to the client
     * @param int $code the HTTP status code
     * @param string $text the status text. If not set, it will be set automatically based on the status code.
     */
    public static function setResponseCode($code, $text = null) {
        self::$status_code = $code;
        if ($text === null) {
            if (isset(self::$http_statuses[$code]))
                self::$status_text = self::$http_statuses[$code];
            else
                self::$status_code = "";
        } else {
            self::$status_text = $text;
        }
    }

    /**
     * Send the respond to the client
     */
    public static function respond() {

        if (self::$is_sent) {
            return;
        }
        self::sendHeader();
        self::sendCookie();
        self::sendContent();
        self::$is_sent = true;
    }

    private static function sendContent() {
        echo self::$content;
    }

    private static function sendHeader() {
        // test if the header are sent
        if (headers_sent()) {
            throw new \Exception("header already send");
        }
        $header_list = self::headers()->getAll();
        foreach ($header_list as $name => $value) {
            // format the name into uppercase of the first character.
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            header($name.':'.$value);
        }
        // set the response code at the end
        header($_SERVER['SERVER_PROTOCOL']." ".self::$status_code." ".self::$status_text, true);
    }

    private static function sendCookie() {
        foreach (self::cookie()->getAllUpdates() as $name => $obj) {
            if ($obj['delete'] === false) {
                setcookie($name, $obj['value'], $obj['expiration'], $obj['path'], $obj['domain'],
                    $obj['secure'], $obj['httponly']);
            } else {
                setcookie($name, null, time() - 86400);
            }
        }
    }

    /**
     * Send a redirect respond
     * @param string $url the URL that you want to redirect
     * @param bool $permanent choose the http status code, set true to use 301, the default value is false
     */
    public static function redirect($url, $permanent = false) {
        if ($permanent === true) {
            self::setResponseCode(301);
        } else {
            self::setResponseCode(302);
        }
        self::headers()->set('location', $url);
    }

    public static function mime($type) {

        switch ($type) {
            case 'json':    return 'application/json';
            case 'jsonp':   return 'application/javascript';
            case 'xml':     return 'application/xml';
            case 'html':    return 'text/html';
            case 'text':
            case 'txt':     return 'text/plain';
            case 'zip':     return 'application/x-zip-compressed';
            case 'stream':  return 'application/octet-stream';

            default :       return 'application/octet-stream';
        }
    }
}