<?php

namespace Rice\Basic\Support\Traits;

trait Singleton {
    private static $instance = null;

    private function __construct() {
    }

    private function __clone() {
    }

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
