<?php

namespace Rice\Basic\Exception;

use Throwable;
use Rice\Basic\Support\Lang;
use Rice\Basic\Enum\LangEnum;

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
        if (!isset(self::$languages[LangEnum::BASE])) {
            self::$languages[LangEnum::BASE] = Lang::getInstance()->setFileName(LangEnum::BASE)->loadFile();
        }

        $langName = $this::getLangName();
        if (!isset(self::$languages[$langName])) {
            // 语言包重写错误信息
            self::$languages[$langName] = Lang::getInstance()->setFileName($langName)->loadFile();
        }

        if (isset(self::$languages[$message])) {
            $message = self::$languages[$message];
        }
        parent::__construct($message, $code, $previous);
    }
}
