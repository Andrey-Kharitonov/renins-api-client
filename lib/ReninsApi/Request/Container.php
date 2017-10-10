<?php

namespace ReninsApi\Request;

use ReflectionClass;
use ReflectionProperty;

abstract class Container
{
    protected static $rules = [];

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
     * Common validation
     */
    protected function validate() {
    }

    /**
     * Get array of properties recursively.
     * It will check required fields and will execute method validate().
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();

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
            if ($value instanceof Container) {
                $value = $value->toArray();
            }

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

    /**
     * Get XML by properties recursively.
     * It will check required fields and will execute method validate().
     * @return \SimpleXMLElement
     */
    public function toXml(): \SimpleXMLElement {
        $this->validate();
        return null;
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

    protected static function checkLogical($property, $value) {

    }

    protected static function checkAmount($property, $value) {
        if ($value) {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException("Invalid value for {$property}. Allow valid number.");
            }
            $value = (float) $value;
            if ($value < 0) {
                throw new \InvalidArgumentException("Invalid value for {$property}. Allow positive values only.");
            }
        } else {
            $value = null;
        }
        return $value;
    }

}