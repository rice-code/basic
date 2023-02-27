<?php

namespace Rice\Basic\components\Exception;

use Rice\Basic\Support\Lang;
use Throwable;

abstract class BaseException extends \Exception
{
    /**
     * 语言包数组.
     *
     * @var array
     */
    protected static array $languages = [];

    /**
     * 获取语言包文件名称.
     *
     * @return string
     */
    abstract public static function getLangName(): string;

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $langName = $this::getLangName();
        if (!isset(self::$languages[$langName])) {
            // 语言包重写错误信息
            self::$languages[$langName] = Lang::getInstance()->setFileName($langName)->loadFile();
        }

        if (isset(self::$languages[$langName][$message])) {
            $message = self::$languages[$langName][$message];
        }
        parent::__construct($message, $code, $previous);
    }
}
