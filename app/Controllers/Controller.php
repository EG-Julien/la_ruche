<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterfarce;
use Psr\Http\Message\ResponseInterface;

class Controller {
    private $container;
    public $flash;
    public static $DB;

    function __construct($container) {
        $this->container = $container;
    }

    public function render($response, $name, $params = []) {

        $p = $params;

        if (isset($_SESSION['flash']) && !empty($_SESSION['flash'])) {
            $flash = ['flash' => $_SESSION['flash']];
            $p = array_merge($flash, $params);
        }
        unset($_SESSION['flash']);
        $this->container->view->render($response, $name, $p);
    }

    static function setDB($DB) {
        self::$DB = $DB;
    }

    static function getDB() {
        return self::$DB;
    }

    public function flash($type, $msg) {
        $flash[$type] = $msg;
    }
}