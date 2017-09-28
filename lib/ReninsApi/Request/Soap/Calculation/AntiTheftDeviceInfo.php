<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Поисковая система
 *
 * @property string $AntiTheftDeviceBrand - Марка
 * @property string $AntiTheftDeviceModel - Модель
 * @property string $AntiTheftTrackerBrand - Марка закладки
 * @property string $AntiTheftTrackerModel - Модель закладки
 */
class AntiTheftDeviceInfo extends Container
{
    protected $rules = [
        'AntiTheftDeviceBrand' => ['toString', 'required', 'notEmpty'],
        'AntiTheftDeviceModel' => ['toString', 'required', 'notEmpty'],
        'AntiTheftTrackerBrand' => ['toString'],
        'AntiTheftTrackerModel' => ['toString'],
    ];
}