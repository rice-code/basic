<?php


namespace Rice\Basic\Support\Annotation;


use ReflectionClass;
use Rice\Basic\Exception\CommonException;
use Rice\Basic\Support\FileNamespace;

class Annotation
{
    /**
     * 反射类
     * @var ReflectionClass
     */
    private $class;

    /**
     * 属性映射数组
     * @var array
     */
    private $propertyMap;

    /**
     * 命名空间映射数组
     * @var array
     */
    private $fileNamespaceMap = [];

    /**
     * 对象属性解析队列
     * @var array
     */
    private $queue;

    public function execute($class): Annotation
    {
        $this->queue[] = $class;
        while (!empty($this->queue)) {
            $objClass = array_shift($this->queue);
            $this->buildClass($objClass)->analysisAttr();
        }

        return $this;
    }

    /**
     * 构建反射类
     * @param $class
     * @return $this
     * @throws \ReflectionException
     */
    public function buildClass($class): self
    {
        $this->class = new ReflectionClass($class);
        $this->fileNamespaceMap = FileNamespace::getInstance()->matchNamespace($this->class->getName(), $this->class->getFileName())->getNamespaces();
        return $this;
    }

    public function analysisAttr()
    {
        $properties = $this->class->getProperties(\ReflectionProperty::IS_PUBLIC);
        $pattern = '/.*@var\s+(\S+)/';
        $className = $this->class->getName();
        foreach ($properties as $property) {
            $matches = [];
            preg_match($pattern, $property->getDocComment(), $matches);
            if (isset($matches[1]) && !empty($matches[1])) {
                $docProperty = (new Property($matches[1]));
                $this->propertyMap[$className][$property->name] = $docProperty;
                $docProperty->namespace = $this->selectNamespace($docProperty);
            }
        }
    }

    /**
     * @param $property
     * @return string
     */
    public function selectNamespace(Property $property): string
    {
        $fileNamespaceMap = $this->fileNamespaceMap[$this->class->getName()];
        if (class_exists($namespace = $fileNamespaceMap['this'] . '\\' . $property->name)) {
            $property->isClass = true;
            $this->queue[] = $namespace;
            return $namespace;
        }

        if (isset($fileNamespaceMap[$property->name]) && class_exists($namespace = $fileNamespaceMap[$property->name] . '\\' . $property->name)) {
            $property->isClass = true;
            $this->queue[] = $namespace;
            return $namespace;
        }

        return '';
    }

    public function getNamespaceList()
    {
        return $this->fileNamespaceMap;
    }
}