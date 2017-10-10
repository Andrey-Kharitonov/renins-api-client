<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Список выгодоприобретателей
 *
 * @property string $LAST_BENEF_ID
 * @property ContainerCollection $BENEFICIARY
 */
class Beneficiaries extends Container
{
    protected $rules = [
        'LAST_BENEF_ID' => ['toString'],

        'BENEFICIARY' => ['containerCollection:' . Beneficiary::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['LAST_BENEF_ID']);

        //BENEFICIARY into here
        $value = $this->BENEFICIARY;
        if ($value !== null) {
            $value->toXml($xml, 'BENEFICIARY');
        }

        return $this;
    }
}