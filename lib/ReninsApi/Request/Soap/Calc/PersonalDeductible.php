<?php

namespace ReninsApi\Request\Soap\Calc;

use ReninsApi\Request\Container;

/**
 * Personal deductible
 *
 * @property int $Age
 * @property int $Experience
 */
class PersonalDeductible extends Container
{
    protected $rules = [
        'Age' => ['toInteger', 'required', 'min:0'],
        'Experience' => ['toInteger', 'required', 'min:0'],
    ];
}