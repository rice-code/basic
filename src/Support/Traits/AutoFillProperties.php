<?php

namespace Rice\Basic\Support\Traits;

use ReflectionException;
use Rice\Basic\Contracts\CacheContract;
use Rice\Basic\Support\Properties\AutoFillPropertyHandler;
use Rice\Basic\Components\Exception\InternalServerErrorException;
use Rice\Basic\Support\Utils\FrameTypeUtil;

/**
 * 自动填充属性Trait
 * 使用组合模式，通过AutoFillPropertyHandler实现功能，避免修改目标类的构造函数
 */
 trait AutoFillProperties
{
    /**
     * @internal
     * @var AutoFillPropertyHandler|null
     */
    private ?AutoFillPropertyHandler $_autoFillHandler = null;
    /**
     * 自动填充初始化方法 - 使用AutoFillPropertyHandler处理
     * 
     * @param mixed $params 参数数据
     * @param CacheContract|null $cache 缓存实例
     * @throws InternalServerErrorException
     * @throws ReflectionException
     */
    public function autoFillInitialize($params = null, CacheContract $cache = null)
    {
        // 延迟创建处理器实例
        if (is_null($this->_autoFillHandler)) {
            $this->_autoFillHandler = new AutoFillPropertyHandler($this);
        }
        
        // 委托给处理器执行初始化，使用已设置的onlyCurrentClass值
        $this->_autoFillHandler->initialize($params, $cache, $this->isOnlyCurrentClass());

        return $this;
    }

    /**
     * 获取自动填充处理器实例
     * 
     * @return AutoFillPropertyHandler
     */
    public function getAutoFillHandler(): AutoFillPropertyHandler
    {
        if (is_null($this->_autoFillHandler)) {
            $this->_autoFillHandler = new AutoFillPropertyHandler($this);
        }
        
        return $this->_autoFillHandler;
    }

    /**
     * @throws InternalServerErrorException
     * @internal
     */
    protected function handle(): void
    {
        if (!is_null($this->_autoFillHandler)) {
            $this->_autoFillHandler->handle();
        }
    }

    /**
     * @throws InternalServerErrorException
     * @internal
     */
    public function fill(): void
    {
        if (!is_null($this->_autoFillHandler)) {
            $this->_autoFillHandler->fill();
        }
    }

    /**
     * 填充类属性值为类的值
     *
     * @internal
     * @param mixed $property
     * @param mixed $name
     * @param mixed $values
     * @return void
     */
    public function fillClass($property, $name, $values): void
    {
        if (!is_null($this->_autoFillHandler)) {
            $this->_autoFillHandler->fillClass($property, $name, $values);
        }
    }

    /**
     * 填充类属性为数组的值
     *
     * @internal
     * @param mixed $name
     * @param array $values
     * @return void
     */
    public function fillArray($name, array $values): void
    {
        if (!is_null($this->_autoFillHandler)) {
            $this->_autoFillHandler->fillArray($name, $values);
        }
    }
    
    /**
    /**
     * 设置是否只填充当前类的属性（过滤父类属性）
     */
    public function setOnlyCurrentClass(bool $onlyCurrentClass): self
    {
        $this->getAutoFillHandler()->setOnlyCurrentClass($onlyCurrentClass);
        return $this;
    }
    
    /**
     * 获取是否只填充当前类的属性的设置
     */
    public function isOnlyCurrentClass(): bool
    {
        return $this->getAutoFillHandler()->isOnlyCurrentClass();
    }
}
