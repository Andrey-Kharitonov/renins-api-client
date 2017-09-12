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
 */
abstract class Policy extends Container
{
    protected static $rules = [
        'ContractTerm' => ['container', 'required'],
        'Covers' => ['containerCollection'],
        'Vehicle' => ['container', 'required'],
    ];

    protected $ContractTerm;
    protected $Covers;
    protected $Vehicle;
}