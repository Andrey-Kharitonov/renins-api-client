<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Input message
 * todo: доделать по xsd
 *
 * @property GeneralQuoteInfo $GENERAL_QUOTE_INFO
 * @property Seller $SELLER
 * @property Insurant $INSURANT
 * @property ContainerCollection $LIST_OF_CONTEXTS
 */
class InputMessage extends Container
{
    protected $rules = [
        'GENERAL_QUOTE_INFO' => ['container:' . GeneralQuoteInfo::class],
        'SELLER' => ['container:' . Seller::class],
        'INSURANT' => ['container:' . Insurant::class],
        'LIST_OF_CONTEXTS' => ['containerCollection:' . Context::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlTagsExcept($xml, ['LIST_OF_CONTEXTS']);

        if ($this->LIST_OF_CONTEXTS) {
            $added = $xml->addChild('LIST_OF_CONTEXTS');
            $this->LIST_OF_CONTEXTS->toXml($added, 'CONTEXT');
        }

        return $this;
    }

}