<?php

if (!function_exists('dd')) {
    /**
     * @return never
     */
    function dd($vars)
    {
        var_dump($vars);
        exit;
    }
}
