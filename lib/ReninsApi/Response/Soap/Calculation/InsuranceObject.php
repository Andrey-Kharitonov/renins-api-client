<?php
namespace ReninsApi\Response\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Insurance object
 *
 * @property string $Bonus
 * @property string $Sum
 * @property string $Name
 */
class InsuranceObject extends Container
{
    protected $rules = [
        'Bonus' => ['toString'], //unknown type
        'Sum' => ['toString'], //unknown type
        'Name' => ['toString'], //unknown type
    ];
}