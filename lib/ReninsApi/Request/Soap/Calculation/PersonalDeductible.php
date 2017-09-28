<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * персональная франшиза
 *
 * @property int $Age - возраст
 * @property int $Experience - стаж
 */
class PersonalDeductible extends Container
{
    protected $rules = [
        'Age' => ['toInteger', 'required', 'min:0'],
        'Experience' => ['toInteger', 'required', 'min:0'],
    ];
}