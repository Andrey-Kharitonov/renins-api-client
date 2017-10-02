<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные о документе
 *
 * @property string $type - тип
 * @property string $Serial - серия
 * @property string $Number - номер
 */
class Document extends Container
{
    protected $rules = [
        'type' => ['toString', 'required', 'docType'],
        'Serial' => ['toString'],
        'Number' => ['toString', 'required', 'notEmpty'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['type']);
        $this->toXmlTags($xml, ['Serial', 'Number']);

        return $this;
    }
}