<?php

namespace ReninsApi\Request\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Some request.
 * PartnerName and PartnerUId will be replaced! Don't specify it.
 *
 * @property string $PartnerName
 * @property string $PartnerUId
 * @property string $AccountNumber
 * @property string $PrintToken
 * @property ContainerCollection $printingParamsItems
 * @property boolean $isPrintAsOneDocument
 */
class Request extends Container
{
    protected $rules = [
        'PartnerName' => ['toString'],
        'PartnerUId' => ['toString'],
        'AccountNumber' => ['toString'],
        'PrintToken' => ['toString'],
        'printingParamsItems' => ['containerCollection:' . PrintingParams::class],
        'isPrintAsOneDocument' => ['toBoolean'],
    ];
}