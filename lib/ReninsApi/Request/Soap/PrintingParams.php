<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Printing params
 *
 * @property string $DocumentTypeId
 * @property array $DocumentLabels
 */
class PrintingParams extends Container
{
    protected $rules = [
        'DocumentTypeId' => ['toString'],
        'DocumentLabels' => ['array:string'],
    ];
}