<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Document
 *
 * @property int $type
 * @property string $Serial
 * @property string $Number
 */
class Document extends Container
{
    protected static $rules = [
        'type' => ['toString', 'required', 'docType'],
        'Serial' => ['toString'],
        'Number' => ['toString', 'required', 'notEmpty'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $this->toXmlAttributes($xml, ['type']);
        $this->toXmlTags($xml, ['Serial', 'Number']);

        return $this;
    }
}