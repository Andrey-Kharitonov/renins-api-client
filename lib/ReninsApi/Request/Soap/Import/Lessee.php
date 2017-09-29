<?php

namespace ReninsApi\Request\Soap\Import;

/**
 * Лизингополучатель
 *
 * @property LeasingAgreement $LEASING_AGREEMENT - Лизинговый договор.
 */
class Lessee extends ContactInfo
{
    protected function init()
    {
        parent::init();
        $this->rules = array_merge($this->rules, [
            'LEASING_AGREEMENT' => ['container:' . LeasingAgreement::class],
        ]);
    }

    public function toXml(\SimpleXMLElement $xml)
    {
        parent::toXml($xml);
        $this->toXmlTags($xml, ['LEASING_AGREEMENT']);
        return $this;
    }
}