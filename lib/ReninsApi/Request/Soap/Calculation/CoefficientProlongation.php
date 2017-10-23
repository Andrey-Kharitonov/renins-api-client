<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Пролонгация с применением коэффициента пролонгации
 *
 * @property string $ProlongationCoefficient - коэффициент пролонгации (0.6-2)
 */
class CoefficientProlongation extends Container
{
    protected $rules = [
        'ProlongationCoefficient' => ['toDouble', 'required', 'between:0.6|2'],
    ];
}