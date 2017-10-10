<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Создатель  (Создатель-сотрудник / Создатель-партнер)
 *
 * @property string $TYPE
 * @property string $IPFLAG
 * @property Employee $EMPLOYEE
 * @property Partner $PARTNER
 * @property ContainerCollection $MANAGERS - Список ФИО оформляющих сотрудников
 */
class Seller extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'in:EMPLOYEE|PARTNER'],
        'IPFLAG' => ['toYN'],

        'EMPLOYEE' => ['container:' . Employee::class],
        'PARTNER' => ['container:' . Partner::class],
        'MANAGERS' => ['containerCollection:' . Manager::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE', 'IPFLAG']);
        $this->toXmlTags($xml, ['EMPLOYEE', 'PARTNER', 'MANAGERS']);

        return $this;
    }
}