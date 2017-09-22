<?php

namespace ReninsApi\Request\Soap\Calc;

use ReninsApi\Request\Container;

/**
 * Option
 *
 * @property int $type
 * @property string $enabled
 */
class Option extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'min:0'], //0-15
        'enabled' => ['toLogical', 'required'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $xml->addAttribute('type', $this->type);
        $xml[0] = $this->enabled;

        return $this;
    }
}