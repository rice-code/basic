<?php

namespace Rice\Basic\Components\Exception;

use Rice\Basic\Support\Lang;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Annotation\ClassReflector;
use Rice\Basic\Contracts\ExceptionSubjectInterface;
use Rice\Basic\Contracts\ExceptionObserverInterface;
use Rice\Basic\Support\Observers\ExceptionLogObserver;

abstract class BaseException extends \Exception implements ExceptionSubjectInterface
{
    /**
     * 语言包数组.
     *
     * @var array
     */
    protected static array $languages = [];

    /**
     * 观察者集合.
     *
     * @var ExceptionObserverInterface[]
     */
    private array $observers = [];

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

    public function __construct($message = '', $code = 0, \Throwable $previous = null)
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
                    $locale          = Lang::getInstance()->getLocale();

                    // 直接使用不带@前缀的标签名，因为DocComment::matchLabels已经去掉了@前缀
                    $enLabels = $property->getDocLabel('en');
                    $zhLabels = $property->getDocLabel('zh-CN');

                    if ('en' === $locale && !empty($enLabels)) {
                        $message = $enLabels[0];
                    } elseif (!empty($zhLabels)) {
                        $message = $zhLabels[0];
                    } elseif (!empty($property->getDocDesc())) {
                        $message = $property->getDocDesc();
                    }
                }
            }
        }

        parent::__construct($message, $code, $previous);

        // 注册默认观察者（日志）
        $this->attach(new ExceptionLogObserver());

        // 通知所有观察者
        $this->notify($this);
    }

    /**
     * {@inheritDoc}
     */
    public function attach(ExceptionObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(ExceptionObserverInterface $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            function ($item) use ($observer) {
                return $item !== $observer;
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function notify(\Exception $e): void
    {
        foreach ($this->observers as $observer) {
            $observer->handle($e);
        }
    }
}
