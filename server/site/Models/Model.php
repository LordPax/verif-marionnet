<?php

class Model {
    private static $instance = null;

    private function __construct() {
        // include "../include/config.php";
    }

    public static function getModel() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}