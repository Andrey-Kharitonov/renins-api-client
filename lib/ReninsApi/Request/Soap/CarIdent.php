<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Some vehicle
 */
class CarIdent extends Container
{
    protected $licensePlate;
    protected $VIN;
    protected $bodyNumber;
    protected $chassisNumber;

    public function setLicensePlate($value) {
        $value = ($value) ? (string) $value : null;
        $this->licensePlate = $value;
        return $this;
    }

    public function getLicensePlate() {
        return $this->licensePlate;
    }

    public function setVIN($value) {
        $value = ($value) ? (string) $value : null;
        $this->VIN = $value;
        return $this;
    }

    public function getVIN() {
        return $this->VIN;
    }

    public function setBodyNumber($value) {
        $value = ($value) ? (string) $value : null;
        $this->bodyNumber = $value;
        return $this;
    }

    public function getBodyNumber() {
        return $this->bodyNumber;
    }

    public function setChassisNumber($value) {
        $value = ($value) ? (string) $value : null;
        $this->chassisNumber = $value;
        return $this;
    }

    public function getChassisNumber() {
        return $this->chassisNumber;
    }

    public function toXml(): \SimpleXMLElement {
        parent::toXml();

        $xml = new \SimpleXMLElement('<root/>');
        if ($this->licensePlate) {
            $xml->addChild('LicensePlate', $this->licensePlate);
        }
        if ($this->VIN) {
            $xml->addChild('VIN', $this->VIN);
        }
        if ($this->bodyNumber) {
            $xml->addChild('BodyNumber', $this->bodyNumber);
        }
        if ($this->chassisNumber) {
            $xml->addChild('ChassisNumber', $this->chassisNumber);
        }
        return $xml;
    }
}