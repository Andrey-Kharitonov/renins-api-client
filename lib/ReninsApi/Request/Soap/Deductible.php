<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Deductible
 *
 * @property int $type
 * @property int $sum
 */
class Deductible extends Container
{
    protected static $rules = [
        'type' => ['toInteger', 'required', 'notEmpty'], //1-4
        'sum' => ['toInteger', 'required', 'min:0'], //0, 5000, 7500, ..., 50000
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $xml->addAttribute('type', $this->type);
        $xml[0] = $this->sum;

        return $this;
    }

    protected $type;
    protected $sum;
}