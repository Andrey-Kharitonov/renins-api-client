<?php

namespace RetailCrm\Request;

use ReflectionClass;
use ReflectionProperty;

abstract class Container
{
    /**
     * @param array $data - pairs of (key => value)
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            foreach($data as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    /**
     * Get array of properties. It checks for required fields.
     * @return array
     */
    public function toArray()
    {
        $reflectionClass = new ReflectionClass($this);
        $array = [];

        foreach ($reflectionClass->getProperties(
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
        ) as $property) {
            $property->setAccessible(true);
            if ($property->isStatic()) {
                continue;
            }

            $value = $this->{$property->getName()}; //Directly from property
            if ($value !== null) {
                $array[$property->getName()] = $value;
            } else {
                $isReqMethod = 'req' . ucfirst($property->getName());
                if (method_exists($this, $isReqMethod)) {
                    if ($this->{$isReqMethod}()) {
                        throw new PropertyRequiredException('Property "' . get_class($this) . '.' . $property->getName() . '" is required');
                    }
                }
            }
            $property->setAccessible(false);
        }

        return $array;
    }

    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);

        if (method_exists($this, $setter)) {
            return $this->{$setter}($value);
        }

        throw new PropertyNotFoundException('Property "' . get_class($this) . '.' . $name . '" is not found');
    }

    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);
        $getterBool = 'is' . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        } elseif (method_exists($this, $getterBool)) {
            return $this->{$getterBool}();
        }

        throw new PropertyNotFoundException('Property "' . get_class($this) . '.' . $name . '" is not found');
    }

    public function __isset($name)
    {
        $getter = 'get' . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}() !== null;
        }

        return false;
    }
}