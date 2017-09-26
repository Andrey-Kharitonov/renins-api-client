<?php

namespace ReninsApi\Request\Soap\Calculation;

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
    protected $rules = [
        'Drivers' => ['container:' . Drivers::class, 'required'],
        'Insurant' => ['container:' . Insurant::class, 'required'],
        'BeneficiaryType' => ['toInteger', 'participantType'],
    ];
}