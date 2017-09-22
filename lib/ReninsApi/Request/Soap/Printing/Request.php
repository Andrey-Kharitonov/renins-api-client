<?php

namespace ReninsApi\Request\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Print request
 *
 * @property string $PartnerName
 * @property string $AccountNumber
 * @property string $PartnerUId
 * @property string $PrintToken
 * @property ContainerCollection $printingParamsItems
 * @property boolean $isPrintAsOneDocument
 */
class Request extends Container
{
    protected $rules = [
        'PartnerName' => ['toString'], //will be replaced
        'AccountNumber' => ['toString'],
        'PartnerUId' => ['toString'], //will be replaced
        'PrintToken' => ['toString'],
        'printingParamsItems' => ['containerCollection:' . PrintingParams::class],
        'isPrintAsOneDocument' => ['toBoolean'],
    ];
}