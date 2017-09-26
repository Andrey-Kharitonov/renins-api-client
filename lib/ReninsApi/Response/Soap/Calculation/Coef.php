<?php
namespace ReninsApi\Response\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Coef
 *
 * @property string $Name
 * @property double $Value
 */
class Coef extends Container
{
    protected $rules = [
        'Name' => ['toString'],
        'Value' => ['toDouble'],
    ];
}