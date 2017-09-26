<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Some request.
 * PartnerName and PartnerUId will be replaced! Don't specify it.
 *
 * @property string $PartnerName
 * @property string $PartnerUId
 * @property string $AccountNumber
 */
class Request extends Container
{
    protected $rules = [
        'PartnerName' => ['toString'],
        'PartnerUId' => ['toString'],
        'AccountNumber' => ['toString'],
    ];
}