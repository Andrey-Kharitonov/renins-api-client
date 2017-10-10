<?php

namespace ReninsApi\Request;

abstract class Container
{
    protected $rules = [];

    private $data = [];

    private $_filter;
    private $_validator;

    /**
     * @param array $data - pairs of (key => value)
     */
    public function __construct(array $data = [])
    {
        $this->_filter = new Filter($this->rules);
        $this->_validator = new Validator($this->rules);

        $this->init();

        //set data
        foreach($data as $property => $value) {
            $this->set($property, $value);
        }
    }

    /**
     * Create instance from xml
     * @param $xml
     * @return static
     */
    public static function createFromXml($xml) {
        if (!($xml instanceof \SimpleXMLElement)) {
            $xml = new \SimpleXMLElement($xml);
        }

        $cont = new static();
        $cont->fromXml($xml);
        return $cont;
    }

    /**
     * Will be called before set initial data
     */
    protected function init() {
    }

    /**
     * Set property value
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value) {
        if (!isset($this->rules[$name])) {
            throw new ContainerException("Property {$name} not found");
        }

        $this->data[$name] = $this->_filter->filter($value, $name);
        return $this;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Get property value
     *
     * @param $name
     * @param mixed $def
     * @return mixed|null
     */
    public function get($name, $def = null) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return $def;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Get all data
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Clear and init
     */
    public function clear() {
        $this->data = [];
        $this->init();
    }

    /**
     * Recursively validation.
     * @return array
     */
    public function validate() {
        $data = $this->getData();

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
     * Get array of [property => value]  recursively.
     * It will execute method validateThrow() before.
     * @return array
     */
    public function toArray(): array
    {
        $this->validateThrow();

        $data = $this->getData();
        foreach($data as $property => $value) {
            if ($value instanceof Container || $value instanceof ContainerCollection) {
                $data[$property] = $value->toArray();
            }
        }
        return $data;
    }

    /**
     * Get XML by properties recursively.
     * It will execute method validateThrow() before.
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    public function toXml(\SimpleXMLElement $xml) {
        $this->validateThrow();

        $data = $this->getData();
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
     * Helper for typical adding of properties like xml attributes.
     * Use into inheritances of toXml() after validation.
     * @param \SimpleXMLElement $xml
     * @param array $properties
     * @return $this
     */
    protected function toXmlAttributes(\SimpleXMLElement $xml, array $properties) {
        foreach ($properties as $property) {
            $value = $this->get($property);
            if ($value === null) continue;

            if ($value instanceof Container || $value instanceof ContainerCollection) {
                throw new ContainerException('Property "' . get_class($this) . '.' . $property . '" can not be used in Container::toXmlAttributes()');
            } else {
                $xml->addAttribute($property, $value);
            }
        }
        return $this;
    }

    /**
     * Helper for typical adding of properties like xml tags.
     * Use into inheritances of toXml() after validate().
     * @param \SimpleXMLElement $xml
     * @param array $properties
     */
    protected function toXmlTags(\SimpleXMLElement $xml, array $properties) {
        foreach ($properties as $property) {
            $value = $this->get($property);
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
    }

    /**
     * Set properties by xml attributes and children tags.
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    public function fromXml(\SimpleXMLElement $xml) {
        foreach($xml->attributes() as $name => $value) {
            $name = (string) $name;
            $value = (string) $value;
            $this->set($name, $value);
        }

        foreach($xml->children() as $child) {
            $name = $child->getName();
            $value = (string) $child;
            $this->set($name, $value);
        }

        return $this;
    }

    /**
     * Helper for typical import properties from xml attributes.
     * Use into inheritances of fromXml().
     * @param \SimpleXMLElement $xml
     * @param array $properties
     */
    protected function fromXmlAttributes(\SimpleXMLElement $xml, array $properties) {
        foreach($xml->attributes() as $name => $value) {
            if (!in_array($name, $properties)) continue;

            $name = (string) $name;
            $value = (string) $value;
            $this->set($name, $value);
        }
    }

    /**
     * Helper for typical import properties from xml children tags.
     * Use into inheritances of fromXml().
     * @param \SimpleXMLElement $xml
     * @param array $properties
     */
    protected function fromXmlTags(\SimpleXMLElement $xml, array $properties) {
        foreach($xml->children() as $child) {
            $name = $child->getName();
            if (!in_array($name, $properties)) continue;

            $value = (string) $child;
            $this->set($name, $value);
        }
    }

}