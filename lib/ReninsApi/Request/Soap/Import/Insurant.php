<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Страхователь
 * todo: доделать по xsd
 *
 * @property string $TYPE
 * @property Contact $CONTACT
 */
class Insurant extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'personType'],

        'CONTACT' => ['container:' . Contact::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE']);
        $this->toXmlTags($xml, ['CONTACT']);
        return $this;
    }
}