<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * Document type info
 *
 * @property integer $Id
 * @property string $Name
 * @property string $Labels
 */
class PolicyDocumentType extends Container
{
    protected $rules = [
        'Id' => ['toInteger', 'required', 'notEmpty'],
        'Name' => ['toString', 'required', 'notEmpty'],
        'Labels' => ['toString'],
    ];
}