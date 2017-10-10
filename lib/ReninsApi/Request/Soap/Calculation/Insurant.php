<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные по страхователю.
 *
 * @property int $type - физическое (1) или  юридическое лицо (2)
 * @property string $INN - ИНН страхователя.
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