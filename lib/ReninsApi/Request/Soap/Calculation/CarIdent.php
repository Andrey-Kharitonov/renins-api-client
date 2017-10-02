<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Идентификатор ТС в РСА.
 * Должно быть заполнено одно из полей.
 *
 * @property string $LicensePlate - Номерной знак (гос.номер) ТС.
 * @property string $VIN - VIN, уникальный код ТС.
 * @property string $BodyNumber - Номер кузова ТС.
 * @property string $ChassisNumber - Номер шасси ТС.
 */
class CarIdent extends Container
{
    protected $rules = [
        'LicensePlate' => ['toString', 'length:|30'],
        'VIN' => ['toString', 'length:|17'],
        'BodyNumber' => ['toString', 'length:|24'],
        'ChassisNumber' => ['toString', 'length:|24'],
    ];

    public function validate()
    {
        $errors = parent::validate();

        if ($this->LicensePlate == ''
            && $this->VIN == ''
            && $this->BodyNumber == ''
            && $this->ChassisNumber == '') {
            $errors['VIN'][] = "One of field must be specified";
        }

        return $errors;
    }
}