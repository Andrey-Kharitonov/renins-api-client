<?php

namespace ReninsApi\Request;

use Iterator;

class ContainerCollection implements Iterator
{
    /**
     * @var Container[]
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @param Container[] $data
     */
    public function __construct(array $data = [])
    {
        foreach($data as $item) {
            if (!($item instanceof Container)) {
                throw new \InvalidArgumentException("Data must be an array of Container");
            }
            $this->addContainer($item);
        }
    }

    /**
     * Add container
     * @param Container $container
     * @return $this
     */
    public function addContainer(Container $container) {
        $this->items[] = $container;
        return $this;
    }

    /**
     * Find container
     * @param Container $container
     * @return bool|int - false: if not exists
     */
    public function find(Container $container) {
        $found = false;
        foreach($this->items as $index => $item) {
            if ($item === $container) {
                $found = $index;
                break;
            }
        }
        return $found;
    }

    /**
     * Clear
     * @return $this
     */
    public function clear() {
        $this->items = [];
        $this->position = 0;
        return $this;
    }

    /**
     * Get count of items
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * Validate each item
     * @return array
     */
    public function validate() {
        $errors = [];

        foreach($this->items as $index => $item) {
            $errorsVal = $item->validate();
            foreach ($errorsVal as $propertyVal => $errorVal) {
                $errors[$index . '.' . $propertyVal] = $errorVal;
            }
        }

        return $errors;
    }

    /**
     * Get array of items recursively
     * @return array
     */
    public function toArray(): array
    {
        $ret = [];
        foreach($this->items as $item) {
            $ret[] = $item->toArray();
        }
        return $ret;
    }

    /**
     * Get XML items recursively
     * @param \SimpleXMLElement $xml
     * @param string $tagName
     * @return $this
     */
    public function toXml(\SimpleXMLElement $xml, string $tagName) {
        if ($tagName == '') {
            throw new \InvalidArgumentException("Tag name isn't specified");
        }

        foreach($this->items as $item) {
            $added = $xml->addChild($tagName);
            $item->toXml($added);
        }

        return $this;
    }

    /*
     * ITERATOR
     */

    public function rewind() {
        $this->position = 0;
    }

    /**
     * @return Container
     */
    public function current() {
        return $this->items[$this->position];
    }

    /**
     * @return int
     */
    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->items[$this->position]);
    }
}