<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Stoa type
 *
 * @property int $type
 * @property string $enabled
 */
class StoaType extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'notEmpty'], //1-8
        'enabled' => ['toLogical', 'required'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $xml->addAttribute('type', $this->type);
        $xml[0] = $this->enabled;

        return $this;
    }
}