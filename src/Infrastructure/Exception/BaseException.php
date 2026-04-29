<?php

namespace Rice\Basic\Infrastructure\Exception;

use Rice\Basic\Support\Lang;
use Rice\Basic\Support\Properties\Property;
use Rice\Basic\Support\Annotation\ClassReflector;
use Rice\Basic\Contracts\ExceptionSubjectInterface;
use Rice\Basic\Contracts\ExceptionObserverInterface;
use Rice\Basic\Support\Observers\ExceptionLogObserver;

abstract class BaseException extends \Exception implements ExceptionSubjectInterface
{
    protected static array $languages = [];
    private array $observers = [];

    abstract public static function httpStatusCode(): int;
    abstract public static function enumClass(): string;

    protected string $fieldName;

    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $enumClass  = $this::enumClass();
        $properties = (new ClassReflector())->execute($enumClass)->getClassProperties();

        if (isset($properties[$enumClass])) {
            foreach ($properties[$enumClass] as $property) {
                if ($message === $property->getValue()) {
                    $this->fieldName = $message;
                    $locale          = Lang::getInstance()->getLocale();

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
        $this->attach(new ExceptionLogObserver());
        $this->notify($this);
    }

    public function attach(ExceptionObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detach(ExceptionObserverInterface $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            function ($item) use ($observer) {
                return $item !== $observer;
            }
        );
    }

    public function notify(\Exception $e): void
    {
        foreach ($this->observers as $observer) {
            $observer->handle($e);
        }
    }
}
