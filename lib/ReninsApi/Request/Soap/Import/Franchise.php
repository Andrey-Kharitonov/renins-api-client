<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Франшиза
 *
 * @property string $TYPE - тип (персональная, безусловная, со второго страхового случая, виновника)
 * @property string $AGE - Возраст
 * @property string $EXPERIENCE - Стаж
 * @property integer $AMOUNT - Сумма (0, 5000, 7500, 10000, 12500, 15000, 17500, 20000, 22500, 25000, 30000, 37500, 50000)
 */
class Franchise extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'in:персональная|безусловная|со второго страхового случая|виновника'],
        'AGE' => ['toString'],
        'EXPERIENCE' => ['toString'],

        'AMOUNT' => ['toInteger'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE', 'AGE', 'EXPERIENCE']);
        $this->toXmlTags($xml, ['AMOUNT']);
        return $this;
    }
}