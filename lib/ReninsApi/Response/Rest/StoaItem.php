<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;

/**
 * СТОА
 *
 * @property integer $StoaGuidForLink
 * @property string $StoaBrandName
 * @property string $StoaAddress
 */
class StoaItem extends Container
{
    protected $rules = [
        'StoaGuidForLink' => ['toInteger'],
        'StoaBrandName' => ['toString'],
        'StoaAddress' => ['toString'],
    ];
}