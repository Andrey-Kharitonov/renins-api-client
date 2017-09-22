<?php

namespace ReninsApi\Request\Soap\Calc;

use ReninsApi\Request\Container;

/**
 * Anti theft device info
 *
 * @property string $AntiTheftDeviceBrand
 * @property string $AntiTheftDeviceModel
 * @property string $AntiTheftTrackerBrand
 * @property string $AntiTheftTrackerModel
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