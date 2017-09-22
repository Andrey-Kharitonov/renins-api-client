<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Выгодоприобретатель (физлицо / юрлицо)
 *
 * @property string $TYPE - физлицо / юрлицо
 * @property string $BENEF_ID
 * @property string $ADDITIONAL_TERMS_STATUS - Выгобоприобретатель указывается в особых условиях
 * @property CONTACT $CONTACT
 */
class BENEFICIARY extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'personType'],
        'BENEF_ID' => ['toString'],
        'ADDITIONAL_TERMS_STATUS' => ['toYN'],

        'CONTACT' => ['container:' . CONTACT::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE', 'BENEF_ID', 'ADDITIONAL_TERMS_STATUS']);
        $this->toXmlTags($xml, ['CONTACT']);
        return $this;
    }
}