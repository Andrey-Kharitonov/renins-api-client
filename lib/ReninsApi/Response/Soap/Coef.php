<?php
namespace ReninsApi\Response\Soap;

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
    protected static $rules = [
        'Name' => ['toString'],
        'Value' => ['toDouble'],
    ];
}