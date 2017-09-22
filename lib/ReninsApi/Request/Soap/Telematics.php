<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Telematics
 *
 * @property string $enabled
 * @property int $Points
 * @property int $LossCount
 */
class Telematics extends Container
{
    protected $rules = [
        'enabled' => ['toLogical', 'required'],
        'Points' => ['toInteger'],
        'LossCount' => ['toInteger'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['enabled']);
        $this->toXmlTags($xml, ['Points', 'LossCount']);

        return $this;
    }
}