<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Risk
 *
 * @property string $Name
 * @property string $Bonus
 * @property string $Sum
 * @property string $TakeIntoRate
 * @property ContainerCollection $Coefs
 */
class Risk extends Container
{
    protected static $rules = [
        'Name' => ['toString'],
        'Bonus' => ['toString'], //unknown type
        'Sum' => ['toString'], //unknown type
        'TakeIntoRate' => ['toString'],
        'Coefs' => ['containerCollection'],
    ];
}