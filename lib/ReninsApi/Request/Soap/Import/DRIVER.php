<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Водитель (физлицо)
 *
 * @property double $KBM - Коэффициент бонус-малус для водителя
 * @property CONTACT $CONTACT
 */
class DRIVER extends Container
{
    protected $rules = [
        'KBM' => ['toDouble', 'min:0'],

        'CONTACT' => ['container:' . CONTACT::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['KBM']);
        $this->toXmlTags($xml, ['CONTACT']);
        return $this;
    }
}