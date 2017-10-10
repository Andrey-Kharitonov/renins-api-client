<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Container;
use Respect\Validation\Validator;

/**
 * Some vehicle
 *
 * @property $manufacturer string
 * @property $model string
 * @property $antiTheftDeviceInfo string
 * @property $PUUDeviceInfo string
 * @property $manufacturerType string
 * @property $isNew string
 * @property $carIdent Container
 */
class Vehicle extends Container
{
    protected static $rules = [
        'isNew' => 'logical',
    ];

    protected $manufacturer;
    protected $model;
    protected $antiTheftDeviceInfo;
    protected $PUUDeviceInfo;
    protected $manufacturerType;
    protected $isNew;

    /**
     * @var Container
     */
    protected $carIdent;

    public function setManufacturer($value) {
        $this->manufacturer = ($value !== null) ? (string) $value : null;
        return $this;
    }

    public function getManufacturer() {
        return $this->manufacturer;
    }

    public function setModel($value) {
        $this->model = ($value !== null) ? (string) $value : null;
        return $this;
    }

    public function getModel() {
        return $this->model;
    }

    public function setAntiTheftDeviceInfo($value) {
        $this->antiTheftDeviceInfo = ($value !== null) ? (string) $value : null;
        return $this;
    }

    public function getAntiTheftDeviceInfo() {
        return $this->antiTheftDeviceInfo;
    }

    public function setPUUDeviceInfo($value) {
        $this->PUUDeviceInfo = ($value !== null) ? (string) $value : null;
        return $this;
    }

    public function getPUUDeviceInfo() {
        return $this->PUUDeviceInfo;
    }

    public function setManufacturerType($value) {
        if ($value !== null) {
            $value = (int) $value;
            if ($value != 0 && $value != 1) {
                throw new \InvalidArgumentException("Invalid value for manufacturerType. Allow 0, 1, null.");
            }
        }
        $this->manufacturerType = $value;
        return $this;
    }

    public function getManufacturerType() {
        return $this->manufacturerType;
    }


    public function setCarIdent(CarIdent $value) {
        $this->carIdent = $value;
        return $this;
    }

    public function getCarIdent() {
        return $this->carIdent;
    }

    public function setIsNew($value) {
        Validator::

        $this->isNew = $value;
        return $this;
    }

    public function getIsNew() {
        return $this->isNew;
    }

    public function toXml(): \SimpleXMLElement {
        parent::toXml();

        $xml = new \SimpleXMLElement('<root/>');
        if ($this->manufacturer !== null) {
            $xml->addChild('Manufacture', $this->manufacturer);
        }
        if ($this->model !== null) {
            $xml->addChild('Model', $this->model);
        }
        if ($this->antiTheftDeviceInfo !== null) {
            $xml->addChild('AntiTheftDeviceInfo', $this->antiTheftDeviceInfo);
        }
        if ($this->PUUDeviceInfo !== null) {
            $xml->addChild('PUUDeviceInfo', $this->PUUDeviceInfo);
        }
        if ($this->manufacturerType !== null) {
            $xml->addChild('ManufacturerType', $this->manufacturerType);
        }
        if ($this->isNew) {
            $xml->addChild('IsNew', $this->isNew);
        }

        if ($this->carIdent) {
            $added = $xml->addChild('CarIdent');
            foreach($this->carIdent->toXml()->children() as $child) {
                Utils::sxmlAppend($added, $child);
            }
        }

        return $xml;
    }
}