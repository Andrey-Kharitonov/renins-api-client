<?php

namespace ReninsApi\Request\Soap;

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
    protected static $rules = [
        'AntiTheftDeviceBrand' => ['toString', 'required', 'notEmpty'],
        'AntiTheftDeviceModel' => ['toString', 'required', 'notEmpty'],
        'AntiTheftTrackerBrand' => ['toString'],
        'AntiTheftTrackerModel' => ['toString'],
    ];

    protected $AntiTheftDeviceBrand;
    protected $AntiTheftDeviceModel;
    protected $AntiTheftTrackerBrand;
    protected $AntiTheftTrackerModel;
}