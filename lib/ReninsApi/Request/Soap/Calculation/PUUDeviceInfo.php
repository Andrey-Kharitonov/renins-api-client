<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Anti theft device info
 *
 * @property string $PUUDeviceModel
 * @property string $AutoStart
 */
class PUUDeviceInfo extends Container
{
    protected $rules = [
        'PUUDeviceModel' => ['toString', 'required', 'notEmpty'],
        'AutoStart' => ['toLogical'],
    ];
}