<?php

namespace ReninsApi\Request\Soap\Import;

/**
 * Выгодоприобретатель (физлицо / юрлицо)
 *
 * @property string $BENEF_ID
 * @property string $ADDITIONAL_TERMS_STATUS - Выгобоприобретатель указывается в особых условиях
 */
class Beneficiary extends ContactInfo
{
    protected function init()
    {
        parent::init();
        $this->rules = array_merge($this->rules, [
            'BENEF_ID' => ['toString'],
            'ADDITIONAL_TERMS_STATUS' => ['toYN'],
        ]);
    }

    public function toXml(\SimpleXMLElement $xml)
    {
        parent::toXml($xml);
        $this->toXmlAttributes($xml, ['BENEF_ID', 'ADDITIONAL_TERMS_STATUS']);
        return $this;
    }
}