<?php

namespace Rice\Basic\Support\Traits;

use Rice\Basic\Support\Utils\StrUtil;
use Rice\Basic\Components\Enum\BaseEnum;
use Rice\Basic\Components\Enum\NameTypeEnum;
use Rice\Basic\Components\Entity\FrameEntity;
use Rice\Basic\Components\Exception\InternalServerErrorException;

trait Accessor
{
    use MagicMethodManager;

    /**
     * Accessor特性标识
     * 用于标记类使用了Accessor特性，以便在MagicMethodManager中识别.
     *
     * @internal
     * @var bool
     */
    protected bool $_hasAccessor = true;

    /**
     * 默认开启 setter.
     *
     * @internal
     * @var bool
     */
    protected bool $_setter = true;

    /**
     * 默认开启 getter.
     *
     * @internal
     */
    protected bool $_getter = true;

    /**
     * 默认属性对象只读.
     *
     * @internal
     * @var bool
     */
    protected bool $_readOnly = true;

    /**
     * 重置Accessor设置（内部方法，用于其他trait覆盖）
     * 注：其他trait可以覆盖此方法来自定义Accessor的行为.
     */
    protected function resetAccessor(): void
    {
        // 默认实现为空，其他trait可以覆盖此方法
    }

    /**
     * @internal
     * @return string
     */
    public function getAuth(): string
    {
        $pattern = '/^([sg]et)(.*)/';

        if ($this->_getter && !$this->_setter) {
            $pattern = '/^(get)(.*)/';
        }

        if (!$this->_getter && $this->_setter) {
            $pattern = '/^(set)(.*)/';
        }

        return $pattern;
    }

    /**
     * @internal
     * @param $attrName
     * @param $args
     * @return void
     */
    protected function setValue($attrName, $args): void
    {
        $this->{$attrName} = $args[0];
    }

    /**
     * @internal
     * @param $attrName
     * @return mixed
     */
    protected function getValue($attrName)
    {
        // 检查属性是否存在
        if (!property_exists($this, $attrName)) {
            throw new InternalServerErrorException(BaseEnum::METHOD_NOT_DEFINE);
        }

        // 只读，因为对象 return 出去可以修改内部值，破坏封装性
        if ($this->_readOnly && isset($this->{$attrName}) && is_object($this->{$attrName})) {
            return clone $this->{$attrName};
        }

        // 安全返回属性值，如果未设置则返回null
        return $this->{$attrName} ?? null;
    }

    /**
     * @internal
     * @param object $obj
     * @param array  $fields
     * @param int    $nameType
     * @return array
     */
    private function assignElement(object $obj, array $fields, int $nameType, array &$processed = []): array
    {
        // 检测循环引用
        $objId = spl_object_id($obj);
        if (isset($processed[$objId])) {
            return $processed[$objId];
        }

        // 初始化结果数组，避免在null上访问数组偏移量
        $result = [];

        $oReflectionClass = new \ReflectionClass($obj);
        foreach ($oReflectionClass->getProperties() as $property) {
            $key = $property->getName();

            // 过滤框架内部定义字段
            if (FrameEntity::inFilter($key)) {
                continue;
            }

            switch ($nameType) {
                case NameTypeEnum::CAMEL_CASE:
                    $key = StrUtil::snakeCaseToCamelCase($key);

                    break;
                case NameTypeEnum::SNAKE_CASE:
                    $key = StrUtil::camelCaseToSnakeCase($key);

                    break;
            }

            // 反射 private, protect 可见开启，保证能够获取属性值 （php8.1 默认开启）
            $property->setAccessible(true);
            $val = $property->getValue($obj);

            if (is_object($val)) {
                // 标记当前对象为正在处理
                $processed[$objId] = []; // 临时占位符
                $val               = $this->assignElement($val, $fields, $nameType, $processed);
            }

            if (is_array($val) && isset($val[0]) && is_object($val[0])) {
                $tempVal = [];
                foreach ($val as $item) {
                    $tempVal[] = $this->assignElement($item, $fields, $nameType, $processed);
                }
                $val = $tempVal;
            }

            if (empty($fields)) {
                $result[$key] = $val;
            } elseif (in_array($key, $fields, true)) {
                $result[$key] = $val;
            }
        }

        // 缓存结果并返回
        $processed[$objId] = $result;

        return $processed[$objId];
    }

    /**
     * @internal
     * @param $fields
     * @return array
     */
    public function toArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::UNLIMITED);
    }

    /**
     * @internal
     * @param $fields
     * @return array
     */
    public function toSnakeCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::SNAKE_CASE);
    }

    /**
     * @internal
     * @param $fields
     * @return array
     */
    public function toCamelCaseArray($fields = []): array
    {
        return $this->assignElement($this, $fields, NameTypeEnum::CAMEL_CASE);
    }
}
