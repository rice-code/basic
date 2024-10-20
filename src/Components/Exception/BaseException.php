<?php

namespace Rice\Basic\Components\Exception;

use Throwable;
use Rice\Basic\Support\Lang;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Annotation\ClassReflector;

abstract class BaseException extends \Exception
{
    /**
     * 语言包数组.
     *
     * @var array
     */
    protected static array $languages = [];

    /**
     * 获取 http 状态码.
     *
     * @return int
     */
    abstract public static function httpStatusCode(): int;

    /**
     * 获取语言包文件名称.
     *
     * @return string
     */
    abstract public static function enumClass(): string;

    /**
     * 枚举类字段名称.
     *
     * @var string
     */
    protected string $fieldName;

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $enumClass  = $this::enumClass();
        $properties = (new ClassReflector())->execute($enumClass)->getClassProperties();

        if (isset($properties[$enumClass])) {
            /**
             * @var Property $property
             */
            foreach ($properties[$enumClass] as $property) {
                if ($message === $property->getValue()) {
                    $this->fieldName = $message;
                    $message         = $property->getDocLabel(Lang::getInstance()->getLocale())[0];
                }
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
