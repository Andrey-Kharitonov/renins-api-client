<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * CalcKBMRequest ?
 *
 * @property string $DateKBM - Дата + время расчета КБМ (Заполняется датой начала действия договора, для которого рассчитывается КБМ).
 * @property ContainerCollection $PhysicalPersons
 * @property JuridicalPerson $JuridicalPerson
 * @property string $OwnerChanged - Произошла смена собственника ТС
 */
class CalcKBMRequest extends Container
{
    protected $rules = [
        'DateKBM' => ['toString', 'required', 'dateTime'],
        'PhysicalPersons' => ['containerCollection:' . PhysicalPerson::class, 'length:1|'],
        'JuridicalPerson' => ['container:' . JuridicalPerson::class],
        'OwnerChanged' => ['toLogical'],
    ];
}