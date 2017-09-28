<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Период использования.
 *
 * @property string $UseDateBegin
 * @property string $UseDateEnd
 */
class Period extends Container
{
    protected $rules = [
        'UseDateBegin' => ['toString', 'date', 'required', 'notEmpty'],
        'UseDateEnd' => ['toDouble', 'date', 'required', 'notEmpty'],
    ];
}