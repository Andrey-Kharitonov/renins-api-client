<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Participants
 *
 * @property Drivers $Drivers
 */
abstract class Participants extends Container
{
    protected static $rules = [
        'Drivers' => ['container', 'required'],
    ];

    protected $Drivers;
}