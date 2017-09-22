<?php

namespace ReninsApi\Request\Soap\Calc;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Policy
 *
 * @property ContractTerm $ContractTerm
 * @property ContainerCollection $Covers
 * @property Vehicle $Vehicle
 * @property Participants $Participants
 * @property Casco $Casco
 */
class Policy extends Container
{
    protected $rules = [
        'ContractTerm' => ['container:' . ContractTerm::class, 'required'],
        'Covers' => ['containerCollection:' . Cover::class],
        'Vehicle' => ['container:' . Vehicle::class, 'required'],
        'Participants' => ['container:' . Participants::class, 'required'],
        'Casco' => ['container:' . Casco::class, 'required'],
    ];
}