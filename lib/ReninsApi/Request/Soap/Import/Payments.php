<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные по плану платежей
 *
 * @property string $PREMIUM_PLAN - План премий (вид рассрочки)
 * @property ContainerCollection $PAYMENT
 */
class Payments extends Container
{
    protected $rules = [
        'PREMIUM_PLAN' => ['toString', 'required', 'notEmpty'],
        'PAYMENT' => ['containerCollection:' . Payment::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['PREMIUM_PLAN']);

        if ($this->PAYMENT !== null) {
            $this->PAYMENT->toXml($xml, 'PAYMENT');
        }

        return $this;
    }

}