<?php

namespace ReninsApi\Request\Soap;

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
    protected static $rules = [
        'type' => ['toInteger', 'required', 'participantType'],
        'INN' => ['toString'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $this->toXmlAttributes($xml, ['type']);
        $this->toXmlTags($xml, ['INN']);

        return $this;
    }

    protected $type;
    protected $INN;
}