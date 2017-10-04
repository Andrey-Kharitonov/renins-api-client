<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * Error info
 *
 * @property integer $Code
 * @property string $Level
 * @property string $Message
 */
class Error extends Container
{
    protected $rules = [
        'Code' => ['toInteger'],
        'Level' => ['toString'],
        'Message' => ['toString', 'required', 'notEmpty'],
    ];
}