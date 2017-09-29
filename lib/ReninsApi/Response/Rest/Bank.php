<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;

/**
 * Bank
 *
 * @property integer $BankId
 * @property string $Name
 */
class Bank extends Container
{
    protected $rules = [
        'BankId' => ['toInteger'],
        'Name' => ['toString'],
    ];
}