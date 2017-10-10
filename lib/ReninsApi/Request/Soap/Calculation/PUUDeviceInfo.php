<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Противоугонная система
 *
 * @property string $PUUDeviceModel - Модель противоугонной системы
 */
class PUUDeviceInfo extends Container
{
    protected $rules = [
        'PUUDeviceModel' => ['toString', 'required', 'notEmpty'],
        //'AutoStart' => ['toLogical'], The element 'PUUDeviceInfo' has invalid child element 'AutoStart'. List of possible elements expected: 'PUUDeviceModel'
    ];
}