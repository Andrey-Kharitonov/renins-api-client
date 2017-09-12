<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Some vehicle
 *
 * @property string $Manufacturer
 * @property string $Model
 * @property AntiTheftDeviceInfo $AntiTheftDeviceInfo
 * @property PUUDeviceInfo $PUUDeviceInfo
 * @property int $ManufacturerType
 * @property string $IsNew
 * @property CarIdent $CarIdent
 */
class Vehicle extends Container
{
    protected static $rules = [
        'Manufacturer' => ['toString', 'required', 'notEmpty'],
        'Model' => ['toString', 'required', 'notEmpty'],
        'AntiTheftDeviceInfo' => ['container'],
        'PUUDeviceInfo' => ['container'],
        'manufacturerType' => ['toInteger', 'required', 'in:0,1'],
        'isNew' => ['toLogical'],
        'CarIdent' => ['container'],
    ];

    protected $manufacturer;
    protected $model;
    protected $antiTheftDeviceInfo;
    protected $PUUDeviceInfo;
    protected $manufacturerType;
    protected $isNew;
    protected $carIdent;

}