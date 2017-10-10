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
        'ContractTerm' => ['toContainer:ContractTerm', 'container', 'required'],
        'Covers' => ['toContainerCollection:Cover', 'containerCollection:Cover'],
        'Vehicle' => ['toContainer:Vehicle', 'container', 'required'],
        'Participants' => ['toContainer:Participants', 'container', 'required'],
        'Casco' => ['toContainer:Casco', 'container', 'required'],
    ];

    protected $ContractTerm;
    protected $Covers;
    protected $Vehicle;
    protected $Participants;
    protected $Casco;
}