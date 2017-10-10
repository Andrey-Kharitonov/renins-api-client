<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Some vehicle
 *
 * @property string $Manufacturer
 * @property string $Model
 * @property int $Year
 * @property double $Cost
 * @property string $Type
 * @property AntiTheftDeviceInfo $AntiTheftDeviceInfo
 * @property PUUDeviceInfo $PUUDeviceInfo
 * @property int $ManufacturerType
 * @property string $IsNew
 * @property int $Power
 * @property string $CarBodyType
 * @property CarIdent $CarIdent
 */
class Vehicle extends Container
{
    protected static $rules = [
        'Manufacturer' => ['toString', 'required', 'notEmpty'],
        'Model' => ['toString', 'required', 'notEmpty'],
        'Year' => ['toInteger', 'required', 'notEmpty'],
        'Cost' => ['toDouble', 'required', 'notEmpty', 'min:0'],
        'Type' => ['toString', 'required', 'vehicleType'],
        'AntiTheftDeviceInfo' => ['container'],
        'PUUDeviceInfo' => ['container'],
        'ManufacturerType' => ['toInteger', 'required', 'in:0|1'],
        'IsNew' => ['toLogical'],
        'Power' => ['toInteger', 'required', 'notEmpty'],
        'CarBodyType' => ['toString', 'required', 'notEmpty'],
        'CarIdent' => ['container'],
    ];

    protected $Manufacturer;
    protected $Model;
    protected $Year;
    protected $Cost;
    protected $Type;
    protected $AntiTheftDeviceInfo;
    protected $PUUDeviceInfo;
    protected $ManufacturerType;
    protected $IsNew;
    protected $Power;
    protected $CarBodyType;
    protected $CarIdent;
}