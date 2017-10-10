<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Message
 *
 * @property int $code
 * @property string $level
 * @property string $text
 */
class Message extends Container
{
    protected $rules = [
        'code' => ['toInteger'],
        'level' => ['toString'], //'', 'Critical', 'Warning' ...
        'text' => ['toString'],
    ];
}