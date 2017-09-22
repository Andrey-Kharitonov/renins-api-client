<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Создатель  (Создатель-сотрудник / Создатель-партнер)
 *
 * @property string $TYPE
 * @property string $IPFLAG
 * @property EMPLOYEE $EMPLOYEE
 * @property PARTNER $PARTNER
 * @property ContainerCollection $MANAGERS - Список ФИО оформляющих сотрудников
 */
class SELLER extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'in:EMPLOYEE|PARTNER'],
        'IPFLAG' => ['toYN'],

        'EMPLOYEE' => ['container:' . EMPLOYEE::class],
        'PARTNER' => ['container:' . PARTNER::class],
        'MANAGERS' => ['containerCollection:' . MANAGER::class],
    ];
}