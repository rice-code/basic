<?php


namespace Rice\Basic\Support;


use ReflectionClass;
use Rice\Basic\Exception\CommonException;

class Annotation
{
    /**
     * @var ReflectionClass
     */
    private $class;

    private $propertyMap;

    private $fileNamespaceMap = [];

    /**
     * 构建反射类
     * @param $class
     * @return $this
     * @throws \ReflectionException
     */
    public function buildClass($class): self
    {
        $this->class = new ReflectionClass($class);
        $this->fileNamespaceMap = (new FileNamespace())->matchNamespace($this->class->getFileName());
        return $this;
    }

    public function analysisAttr()
    {
        $properties = $this->class->getProperties(\ReflectionProperty::IS_PUBLIC);
        $pattern = '/.*@var\s+(\w+)/';
        foreach ($properties as $property) {
            var_dump($property);
            var_dump($property->getDocComment());
            $matches = [];
            $this->propertyMap[$property->name] = preg_match($pattern, $property->getDocComment(), $matches);
            var_dump($matches);
            if ($matches[1] == 'string') continue;
            var_dump((new ReflectionClass($matches[1]))->getShortName());
            var_dump(class_exists($matches[1]));
        }
    }

    /**
     * @param $name
     * @return string
     * @throws CommonException
     */
    public function selectNamespace($name)
    {
        if (class_exists($namespace = $this->fileNamespaceMap['this'] . '\\' . $name)) {
            return $namespace;
        } elseif (isset($this->fileNamespaceMap[$name]) && class_exists($namespace = $this->fileNamespaceMap[$name] . '\\' . $name)) {
            return $namespace;
        }

        throw new CommonException(CommonException::CLASS_DOES_NOT_EXIST);
    }
}