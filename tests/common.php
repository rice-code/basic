<?php

if (!function_exists('dd')) {
    function dd($vars)
    {
        var_dump($vars);
        exit();
    }
}
