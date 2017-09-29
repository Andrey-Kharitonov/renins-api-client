<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Лизинговый договор.
 *
 * @property string $NUMBER
 * @property string $ISSUE_DATE
 */
class LeasingAgreement extends Container
{
    protected $rules = [
        'NUMBER' => ['toString', 'required', 'notEmpty'],
        'ISSUE_DATE' => ['toString', 'required', 'date'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributesExcept($xml, []);
        return $this;
    }
}