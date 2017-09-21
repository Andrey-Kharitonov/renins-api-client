<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Contract term
 *
 * @property int $Product
 * @property string $ProgramType
 * @property int $DurationMonth
 * @property int $PeriodUseMonth
 * @property int $PaymentType
 * @property string $Currency - default RUR
 * @property string $Purpose
 */
class ContractTerm extends Container
{
    protected $rules = [
        'Product' => ['toInteger', 'required', 'between:1,6'],
        'ProgramType' => ['toString', 'required', 'notEmpty'],
        'DurationMonth' => ['toInteger', 'required', 'min:1'],
        'PeriodUseMonth' => ['toInteger', 'between:3,12'], //OSAGO
        'PaymentType' => ['toInteger', 'required', 'min:1'],
        'Currency' => ['toString', 'required', 'in:RUR'],
        'Purpose' => ['toString', 'required', 'notEmpty'],
    ];

    protected function init()
    {
        $this->set('Currency', 'RUR');
    }
}