<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Some calculation
 *
 * @property int $type
 * @property Policy $Policy
 */
abstract class CalculationRequest extends Container
{
    protected static $rules = [
        'type' => ['toInteger', 'required', 'in:0,1'],
        'Policy' => ['container', 'required'],
    ];

    protected $type;
    protected $Policy;
}