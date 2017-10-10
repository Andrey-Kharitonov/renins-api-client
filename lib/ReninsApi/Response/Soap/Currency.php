<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Currency
 *
 * @property string $Code
 * @property string $Rate
 * @property string $Base
 */
class Currency extends Container
{
    protected $rules = [
        'Code' => ['toString'], //unknown type
        'Rate' => ['toString'], //unknown type
        'Base' => ['toString'], //unknown type
    ];
}