<?php

namespace Rice\Basic\Exception;

use Rice\Basic\Enum\ExceptionEnum;
use Rice\Basic\Support\Lang;
use Throwable;

abstract class BaseException extends \Exception
{

    /**
     * 获取语言包文件名称.
     * @return string
     */
    abstract public static function getLangName(): string;

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        // 语言包重写错误信息
        Lang::getInstance()->setFileName($this::getLangName())->loadFile();

        if (Lang::getInstance()->existKey($message)) {
            $message = Lang::getInstance()->getMessage($message);
        }
        parent::__construct($message, $code, $previous);
    }
}
