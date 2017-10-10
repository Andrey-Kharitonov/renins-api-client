<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Создатель (Создатель-сотрудник / Создатель-партнер)
 *
 * @property string $TYPE - тип
 * @property string $IPFLAG
 * @property Employee $EMPLOYEE - Создатель-сотрудник (штатный сотрудник / агент).
 * @property Partner $PARTNER - Создатель-партнер.
 * @property ContainerCollection $MANAGERS - Список ФИО оформляющих сотрудников
 */
class Seller extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'in:EMPLOYEE|PARTNER'],
        'IPFLAG' => ['toYN'],

        'EMPLOYEE' => ['container:' . Employee::class],
        'PARTNER' => ['container:' . Partner::class],
        'MANAGERS' => ['containerCollection:' . Manager::class, 'length:|1'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE', 'IPFLAG']);
        $this->toXmlTags($xml, ['EMPLOYEE', 'PARTNER', 'MANAGERS']);

        return $this;
    }

    public function validate()
    {
        $errors = parent::validate();

        if ($this->TYPE == 'EMPLOYEE'
            && !$this->EMPLOYEE) {
            $errors['EMPLOYEE'][] = "EMPLOYEE is required for TYPE == EMPLOYEE";
        } elseif ($this->TYPE == 'PARTNER'
            && !$this->PARTNER) {
            $errors['PARTNER'][] = "PARTNER is required for TYPE == PARTNER";
        }

        return $errors;
    }
}