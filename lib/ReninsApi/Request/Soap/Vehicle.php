<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Container;

/**
 * Some vehicle
 */
class Vehicle extends Container
{
    protected static $rules = [
        'manufacturer' => ['toString', 'required'],
        'model' => ['toString', 'required'],
        'antiTheftDeviceInfo' => ['container'],
        'PUUDeviceInfo' => ['container'],
        'manufacturerType' => ['toInteger', 'required', 'in:0,1'],
        'isNew' => ['toLogical'],
        'carIdent' => ['container'],
    ];

    /**
     * @var string
     */
    public $manufacturer;

    /**
     * @var string
     */
    public $model;

    /**
     * @var AntiTheftDeviceInfo
     */
    public $antiTheftDeviceInfo;

    /**
     * @var PUUDeviceInfo
     */
    public $PUUDeviceInfo;

    /**
     * @var int|string
     */
    public $manufacturerType;

    /**
     * @var mixed
     */
    public $isNew;

    /**
     * @var CarIdent
     */
    public $carIdent;


    public function toXml(): \SimpleXMLElement {
        parent::toXml();

        $xml = new \SimpleXMLElement('<root/>');
        if ($this->manufacturer !== null) {
            $xml->addChild('Manufacture', $this->manufacturer);
        }
        if ($this->model !== null) {
            $xml->addChild('Model', $this->model);
        }
        if ($this->antiTheftDeviceInfo) {
            $added = $xml->addChild('AntiTheftDeviceInfo');
            Utils::sxmlAppendContainer($added, $this->antiTheftDeviceInfo);
        }
        if ($this->PUUDeviceInfo) {
            $added = $xml->addChild('PUUDeviceInfo');
            Utils::sxmlAppendContainer($added, $this->PUUDeviceInfo);
        }
        if ($this->manufacturerType !== null) {
            $xml->addChild('ManufacturerType', $this->manufacturerType);
        }
        if ($this->isNew) {
            $xml->addChild('IsNew', $this->isNew);
        }
        if ($this->carIdent) {
            $added = $xml->addChild('CarIdent');
            Utils::sxmlAppendContainer($added, $this->carIdent);
        }

        return $xml;
    }
}