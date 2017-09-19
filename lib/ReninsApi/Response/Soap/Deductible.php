<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Deductible
 *
 * @property string $type
 * @property double $sum
 */
class Deductible extends Container
{
    protected static $rules = [
        'type' => ['toInteger'],
        'sum' => ['toDouble'],
    ];
}