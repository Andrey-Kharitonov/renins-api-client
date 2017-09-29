<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;

/**
 * Leasing
 *
 * @property integer $LeasingId
 * @property string $Name
 */
class Leasing extends Container
{
    protected $rules = [
        'LeasingId' => ['toInteger'],
        'Name' => ['toString'],
    ];
}