<?php

namespace ReninsApi\Request;

use ReflectionClass;
use ReflectionProperty;

abstract class Container
{
    protected static $rules = [];

    protected $_filter;
    protected $_validator;

    /**
     * @param array $data - pairs of (key => value)
     */
    public function __construct(array $data = [])
    {
        $this->_filter = new Filter(static::$rules);
        $this->_validator = new Validator(static::$rules);

        if ($data) {
            //get public properties
            $publicProperties = [];
            $reflectionClass = new ReflectionClass($this);
            foreach ($reflectionClass->getProperties(
                ReflectionProperty::IS_PUBLIC
            ) as $property) {
                if ($property->isStatic()) continue;
                $publicProperties[$property->getName()] = true;
            }

            //set data
            foreach($data as $property => $value) {
                if (isset($publicProperties[$property])) {
                    //public property will be set directly
                    $this->{$property} = $value;
                } else {
                    $this->__set($property, $value);
                }
            }
        }
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;

        if (method_exists($this, $setter)) {
            //via getter
            return $this->{$setter}($value);
        } elseif (property_exists($this, $name)) {
            //directly via filter
            $this->{$name} = $this->_filter->filter($value, $name);
            return $this;
        }

        throw new ContainerException('Property "' . get_class($this) . '.' . $name . '" is not found');
    }

    public function __get($name)
    {
        $getter = 'get' . $name;

        if (method_exists($this, $getter)) {
            //via getter
            return $this->{$getter}();
        } elseif (property_exists($this, $name)) {
            //directly
            return $this->{$name};
        }

        throw new ContainerException('Property "' . get_class($this) . '.' . $name . '" is not found');
    }


    /**
     * Get public, protected, private properties with values. Getter wil be used.
     * @return array
     */
    protected function _getData() {
        $reflectionClass = new ReflectionClass($this);
        $data = [];

        foreach ($reflectionClass->getProperties(
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
        ) as $property) {
            if ($property->isStatic()) continue;
            $name = $property->getName();

            if (substr($name, 0, 1) == '_') continue;

            $getter = 'get' . $name;
            if (method_exists($this, $getter)) {
                $data[$name] = $this->{$getter}();
            } else {
                $data[$name] = $this->{$name};
            }
        }

        return $data;
    }

    /**
     * Recursively validation.
     * @return array
     */
    public function validate() {
        $data = $this->_getData();

        //validate my properties
        $this->_validator->validate($data);
        $errors = $this->_validator->getErrors();

        //validate included containers
        foreach($data as $property => $value) {
            if ($value instanceof Container || $value instanceof ContainerCollection) {
                $errorsVal = $value->validate();
                foreach ($errorsVal as $propertyVal => $errorVal) {
                    $errors[$property . '.' . $propertyVal] = $errorVal;
                }
            }
        }

        return $errors;
    }

    /**
     * Recursively validation with error.
     * @return $this
     */
    public function validateThrow() {
        $errors = $this->validate();
        if ($errors) {
            $exc = new ValidatorMultiException("Invalid data");
            $exc->setErrors($errors);
            throw $exc;
        }
        return $this;
    }

    /**
     * Get array of properties recursively.
     * It will execute method validate() before.
     * @return array
     */
    public function toArray(): array
    {
        $this->validateThrow();

        $data = $this->_getData();
        foreach($data as $property => $value) {
            if ($value instanceof Container || $value instanceof ContainerCollection) {
                $data[$property] = $value->toArray();
            }
        }
        return $data;
    }

    /**
     * Get XML by properties recursively.
     * It will execute method validate() before.
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    public function toXml(\SimpleXMLElement $xml) {
        $this->validateThrow();

        $data = $this->_getData();
        foreach($data as $property => $value) {
            if ($value === null) continue;

            if ($value instanceof Container) {
                $added = $xml->addChild($property);
                $value->toXml($added);
            } elseif ($value instanceof ContainerCollection) {
                $added = $xml->addChild($property);
                $tagName = (substr($property, -1) == 's') ? substr($property, 0, strlen($property) - 1) : $property . 'Item';
                $value->toXml($added, $tagName);
            } else {
                $xml->addChild($property, $value);
            }
        }

        return $this;
    }

    /**
     * Helper for typical adding of properties as attributes.
     * It doesn't use getters!
     * Use only into inheritances of toXml() after validate().
     * @param \SimpleXMLElement $xml
     * @param array $properties
     */
    protected function toXmlAttributes(\SimpleXMLElement $xml, array $properties) {
        foreach ($properties as $property) {
            if (!property_exists($this, $property)) {
                throw new ContainerException('Property "' . get_class($this) . '.' . $property . '" is not found');
            }

            $value = $this->{$property};
            if ($value !== null) {
                if ($value instanceof Container || $value instanceof ContainerCollection) {
                    throw new ContainerException('Property "' . get_class($this) . '.' . $property . '" can not be used in Container::toXmlAttributes()');
                } else {
                    $xml->addAttribute($property, $value);
                }
            }
        }
    }

    /**
     * Helper for typical adding of properties as tags.
     * It doesn't use getters!
     * Use only into inheritances of toXml() after validate().
     * @param \SimpleXMLElement $xml
     * @param array $properties
     */
    protected function toXmlTags(\SimpleXMLElement $xml, array $properties) {
        foreach ($properties as $property) {
            if (!property_exists($this, $property)) {
                throw new ContainerException('Property "' . get_class($this) . '.' . $property . '" is not found');
            }

            $value = $this->{$property};
            if ($value !== null) {
                if ($value instanceof Container) {
                    $added = $xml->addChild($property);
                    $value->toXml($added);
                } elseif ($value instanceof ContainerCollection) {
                    $added = $xml->addChild($property);
                    $tagName = (substr($property, -1) == 's') ? substr($property, 0, strlen($property) - 1) : $property . 'Item';
                    $value->toXml($added, $tagName);
                } else {
                    $xml->addChild($property, $value);
                }
            }
        }
    }
}