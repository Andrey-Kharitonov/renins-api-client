<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Input message
 * todo: доделать по xsd
 *
 * @property GENERAL_QUOTE_INFO $GENERAL_QUOTE_INFO
 * @property SELLER $SELLER
 * @property INSURANT $INSURANT
 * @property ContainerCollection $LIST_OF_CONTEXTS
 */
class InputMessage extends Container
{
    protected $rules = [
        'GENERAL_QUOTE_INFO' => ['container:' . GENERAL_QUOTE_INFO::class],
        'SELLER' => ['container:' . SELLER::class],
        'INSURANT' => ['container:' . INSURANT::class],
        'LIST_OF_CONTEXTS' => ['containerCollection:' . CONTEXT::class],
    ];
}