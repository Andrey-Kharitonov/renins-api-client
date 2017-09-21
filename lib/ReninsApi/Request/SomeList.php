<?php
namespace ReninsApi\Request;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Some list. Has only on attribute list.
 *
 * @property string $list
 */
abstract class SomeList extends Container
{
    protected $rules = [
        'list' => ['toString'],
    ];
}