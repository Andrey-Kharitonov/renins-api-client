<?php

namespace ReninsApi\Request\Soap\Calc;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Document
 *
 * @property int $type
 * @property string $INN
 */
class Insurant extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'participantType'],
        'INN' => ['toString'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['type']);
        $this->toXmlTags($xml, ['INN']);

        return $this;
    }
}