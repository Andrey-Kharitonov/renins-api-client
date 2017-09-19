<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Personal deductible
 *
 * @property int $Age
 * @property int $Experience
 */
class PersonalDeductible extends Container
{
    protected static $rules = [
        'Age' => ['toInteger', 'required', 'min:0'],
        'Experience' => ['toInteger', 'required', 'min:0'],
    ];
}