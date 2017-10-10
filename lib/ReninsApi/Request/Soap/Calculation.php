<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Some calculation
 * @property $type string
 */
abstract class Calculation extends Container
{
    protected $type = 0;
    protected $product;
    protected $programType;

    protected $coverUgon;
    protected $coverUsherb;
    protected $coverDo;
    protected $coverNs;
    protected $coverDago;

    protected $vehicle;

    /*
     * MAIN
     */

    public function setType($value) {
        if ($value !== null) {
            $value = ($value) ? 1 : 0;
        }
        $this->type = $value;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function reqType() {
        return true;
    }

    public function setProduct($value) {
        $allowValues = [1, 2, 3, 4];
        if ($value) {
            $value = (int)$value;
            if (!in_array($value, $allowValues)) {
                throw new \InvalidArgumentException("Invalid value for product. Allow " . implode(', ', $allowValues) . ".");
            }
        } else {
            $value = null;
        }
        $this->product = $value;
        return $this;
    }

    public function getProduct() {
        return $this->product;
    }

    public function reqProduct() {
        return true;
    }

    public function setProgramType($value) {
        $value = (string) $value;
        $this->programType = ($value) ? $value : null;
        return $this;
    }

    public function getProgramType() {
        return $this->programType;
    }

    /*
     * COVERS
     */

    public function setCoverUgon($value) {
        $this->coverUgon = static::checkAmount('coverUgon', $value);
        return $this;
    }

    public function getCoverUgon() {
        return $this->coverUgon;
    }

    public function setCoverUsherb($value) {
        $this->coverUsherb = static::checkAmount('coverUsherb', $value);
        return $this;
    }

    public function getCoverUsherb() {
        return $this->coverUsherb;
    }

    public function setCoverDo($value) {
        $this->coverDo = static::checkAmount('coverDo', $value);
        return $this;
    }

    public function getCoverDo() {
        return $this->coverDo;
    }

    public function setCoverNs($value) {
        $this->coverNs = static::checkAmount('coverNs', $value);
        return $this;
    }

    public function getCoverNs() {
        return $this->coverNs;
    }

    public function setCoverDago($value) {
        $this->coverDago = static::checkAmount('coverDago', $value);
        return $this;
    }

    public function getCoverDago() {
        return $this->coverDago;
    }

    /*
     * Vehicle
     */

    public function setVehicle(Vehicle $value) {
        $this->vehicle = $value;
        return $this;
    }

    public function getVehicle() {
        return $this->vehicle;
    }


}