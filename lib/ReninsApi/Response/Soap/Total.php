<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Total
 *
 * @property string $Sum
 * @property string $Bonus
 */
class Total extends Container
{
    protected static $rules = [
        'Sum' => ['toString'], //unknown type
        'Bonus' => ['toString'], //unknown type
    ];
}