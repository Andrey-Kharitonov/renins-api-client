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
 */
class Policy extends Container
{
    protected static $rules = [
        'ContractTerm' => ['container', 'required'],
        'Covers' => ['containerCollection'],
        'Vehicle' => ['container', 'required'],
        'Participants' => ['container', 'required'],
    ];

    protected $ContractTerm;
    protected $Covers;
    protected $Vehicle;
    protected $Participants;
}