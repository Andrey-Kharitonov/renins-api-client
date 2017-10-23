<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Расчёт пролонгации вручную
 *
 * @property ContainerCollection $Risks - Риски в новом договоре
 */
class ManualProlongation extends Container
{
    protected $rules = [
        'Risks' => ['containerCollection:' . Risk::class],
    ];
}