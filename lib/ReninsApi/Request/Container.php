<?php

namespace ReninsApi\Request;

use ReflectionClass;
use ReflectionProperty;
use ReninsApi\Request\Validator\Validator;
use ReninsApi\Request\Validator\ValidatorException;

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
                $this->{$name} = $value;
            }
        }
    }

    /**
     * Get public properties with values
     * @return array
     */
    public function getData() {
        $reflectionClass = new ReflectionClass($this);
        $data = [];

        foreach ($reflectionClass->getProperties(
            ReflectionProperty::IS_PUBLIC
        ) as $property) {
            if ($property->isStatic()) continue;
            $name = $property->getName();
            $data[$name] = $this->{$name};
        }

        return $data;
    }

    /**
     * Recursively validation.
     * @param bool $throwException
     * @return array
     */
    public function validate($throwException = false) {
        $data = $this->getData();

        //validate my properties
        $validator = new Validator(static::$rules);
        $validator->validate($data);
        $errors = $validator->getErrors();

        //validate included containers
        foreach($data as $property => $value) {
            if ($value instanceof Container) {
                $errorsVal = $value->validate();
                foreach ($errorsVal as $propertyVal => $errorVal) {
                    $errors[$property . '.' . $propertyVal] = $errorVal;
                }
            }
        }

        if ($errors && $throwException) {
            $exc = new ValidatorException("Invalid data");
            $exc->setErrors($errors);
            throw $exc;
        }

        return $errors;
    }

    /**
     * Get array of properties recursively.
     * It will execute method validate() before.
     * @return array
     */
    public function toArray(): array
    {
        $this->validate( true);

        $data = $this->getData();
        foreach($data as $property => $value) {
            if ($value instanceof Container) {
                $data[$property] = $value->toArray();
            }
        }
        return $data;
    }

    /**
     * Get XML by properties recursively.
     * It will execute method validate() before.
     * @return \SimpleXMLElement
     */
    public function toXml(): \SimpleXMLElement {
        $this->validate( true);

        return null;
    }
}