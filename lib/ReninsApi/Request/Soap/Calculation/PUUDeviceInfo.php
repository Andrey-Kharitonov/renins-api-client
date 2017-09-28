<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Противоугонная система
 *
 * @property string $PUUDeviceModel - Модель противоугонной системы
 * @property string $AutoStart - Наличие Авто запуска
 */
class PUUDeviceInfo extends Container
{
    protected $rules = [
        'PUUDeviceModel' => ['toString', 'required', 'notEmpty'],
        'AutoStart' => ['toLogical'],
    ];
}