<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Participants
 *
 * @property Drivers $Drivers
 * @property Insurant $Insurant
 * @property int $BeneficiaryType
 */
class Participants extends Container
{
    protected static $rules = [
        'Drivers' => ['container', 'required'],
        'Insurant' => ['container', 'required'],
        'BeneficiaryType' => ['toInteger', 'participantType'],
    ];

    protected $Drivers;
    protected $Insurant;
    protected $BeneficiaryType;
}