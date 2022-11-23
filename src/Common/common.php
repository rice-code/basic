<?php

use Rice\Basic\Exception\CommonException;

// 大写字母判断
if (!function_exists('is_upper')) {
    function is_upper(string $str)
    {
        if (strlen($str) > 1) {
            throw new CommonException(CommonException::INVALID_PARAM);
        }

        if (preg_match('/^[A-Z]+$/', $str)) {
            return true;
        }

        return false;
    }
}

// 小写字母判断
if (!function_exists('is_lower')) {
    function is_lower(string $str)
    {
        if (strlen($str) > 1) {
            throw new CommonException(CommonException::INVALID_PARAM);
        }

        if (preg_match('/^[a-z]+$/', $str)) {
            return true;
        }

        return false;
    }
}

// 驼峰转蛇形
if (!function_exists('camel_case_to_snake_case')) {
    function camel_case_to_snake_case($name)
    {
        $len = strlen($name);

        if (0 === $len) {
            throw new CommonException(CommonException::INVALID_PARAM);
        }

        $newName = $name[0];
        for ($i = 1; $i < $len; ++$i) {
            $char = $name[$i];
            if (is_upper($char)) {
                $newName .= '_' . strtolower($char);

                continue;
            }

            $newName .= $char;
        }

        return $newName;
    }
}

// 蛇形转驼峰
if (!function_exists('snake_case_to_camel_case')) {
    function snake_case_to_camel_case($name)
    {
        $newName = '';
        $wordArr = explode('_', $name);
        foreach ($wordArr as $word) {
            if ('' == $newName) {
                $newName .= $word;

                continue;
            }
            $newName .= ucfirst($word);
        }

        return $newName;
    }
}
