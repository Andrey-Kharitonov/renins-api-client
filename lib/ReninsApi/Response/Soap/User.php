<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * User
 *
 * @property string $cashbookID
 * @property string $userID
 * @property string $Name
 */
class User extends Container
{
    protected static $rules = [
        'cashbookID' => ['toString'], //long int
        'userID' => ['toString'], //long int
        'Name' => ['toString'], //unknown type
    ];
}