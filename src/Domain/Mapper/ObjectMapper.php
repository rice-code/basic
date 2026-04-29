<?php

namespace Rice\Basic\Domain\Mapper;

use Rice\Basic\Support\Annotation\ClassReflector;
use Rice\Basic\Contracts\CacheContract;

class ObjectMapper
{
    public function toArray(object $object, ?CacheContract $cache = null, bool $useReflection = false): array
    {
        $reflection = new \ReflectionObject($object);

        if ($useReflection) {
            $properties = $reflection->getProperties();
        } else {
            $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        }

        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            $data[$property->getName()] = $this->normalizeValue($value, $cache);
        }

        return $data;
    }

    public function fromArrayToObject($target, array $data, ?CacheContract $cache = null): object
    {
        $object = is_object($target) ? $target : new $target();

        foreach ($data as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $this->denormalizeValue($value, $cache);
            }
        }

        return $object;
    }

    public function fromArrayWithAnnotation(string $className, array $data, ?CacheContract $cache = null): object
    {
        $object = new $className();
        $reflector = new ClassReflector($cache);
        $reflector->execute($className);
        $properties = $reflector->getClassProperties($className);

        foreach ($properties as $name => $property) {
            if (isset($data[$name])) {
                $value = $data[$name];
                if ($property->isClass && is_array($value)) {
                    $value = $this->createNestedObject($property, $value, $cache);
                }
                $object->$name = $value;
            }
        }

        return $object;
    }

    protected function createNestedObject(object $property, array $data, ?CacheContract $cache = null): ?object
    {
        if (empty($property->namespace)) {
            return null;
        }

        $className = $property->namespace;

        if ($property->isArray) {
            $result = [];
            foreach ($data as $item) {
                if (is_array($item)) {
                    $result[] = $this->fromArrayWithAnnotation($className, $item, $cache);
                } else {
                    $result[] = $item;
                }
            }
            return $result;
        }

        return $this->fromArrayWithAnnotation($className, $data, $cache);
    }

    protected function normalizeValue($value, ?CacheContract $cache = null)
    {
        if (is_object($value)) {
            if (method_exists($value, 'toArray')) {
                return $value->toArray();
            }
            if (method_exists($value, 'toArrayWithAnnotation')) {
                return $value->toArrayWithAnnotation($cache);
            }
            return $this->toArray($value, $cache, true);
        }

        if (is_array($value)) {
            return array_map(function ($item) use ($cache) {
                return $this->normalizeValue($item, $cache);
            }, $value);
        }

        return $value;
    }

    protected function denormalizeValue($value, ?CacheContract $cache = null)
    {
        if (is_array($value)) {
            return array_map(function ($item) use ($cache) {
                return $this->denormalizeValue($item, $cache);
            }, $value);
        }

        return $value;
    }
}