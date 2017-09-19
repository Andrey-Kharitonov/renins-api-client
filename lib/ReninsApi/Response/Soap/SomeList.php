<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Some list. Has only on attribute list.
 *
 * @property string $list
 */
class SomeList extends Container
{
    protected static $rules = [
        'list' => ['toString'],
    ];
}