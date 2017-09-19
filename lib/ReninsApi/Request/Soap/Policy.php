<?php

namespace ReninsApi\Request\Soap;

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
    protected static $rules = [
        'ContractTerm' => ['container', 'required'],
        'Covers' => ['containerCollection'],
        'Vehicle' => ['container', 'required'],
        'Participants' => ['container', 'required'],
        'Casco' => ['container', 'required'],
    ];
}