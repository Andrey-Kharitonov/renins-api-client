<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;

/**
 * Has one tag name
 *
 * @property string $Name
 */
abstract class ContainerWithName extends Container
{
    protected $rules = [
        'Name' => ['toString'],
    ];
}