<?php
namespace ReninsApi\Request;

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