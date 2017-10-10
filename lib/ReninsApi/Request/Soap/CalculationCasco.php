<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Some calculation
 *
 * @property int $type
 * @property Policy $Policy
 */
class CalculationCasco extends Container
{
    protected static $rules = [
        'type' => ['toInteger', 'required', 'in:0|1'],
        'Policy' => ['container', 'required'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $this->toXmlAttributes($xml, ['type']);
        $this->toXmlTags($xml, ['Policy']);

        return $this;
    }

    protected $type = 0;
    protected $Policy;
}