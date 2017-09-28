<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Котировка
 *
 * @property string $externalId
 * @property ContractTerm $ContractTerm - Условия договора
 * @property ContainerCollection $Covers - Покрытия
 * @property Vehicle $Vehicle - ТС
 * @property Participants $Participants - Участники договора
 * @property Casco $Casco - Блок для КАСКО
 */
class Policy extends Container
{
    protected $rules = [
        'externalId' => ['toString'],

        'ContractTerm' => ['container:' . ContractTerm::class, 'required'],
        'Covers' => ['containerCollection:' . Cover::class],
        'Vehicle' => ['container:' . Vehicle::class, 'required'],
        'Participants' => ['container:' . Participants::class, 'required'],
        'Casco' => ['container:' . Casco::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['externalId']);
        $this->toXmlTagsExcept($xml, ['externalId']);
        return $this;
    }

}