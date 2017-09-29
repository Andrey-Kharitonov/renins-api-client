<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Сообщение на входе интеграционного  процесса
 *
 * @property GeneralQuoteInfo $GENERAL_QUOTE_INFO
 * @property Seller $SELLER - Создатель (Создатель-сотрудник / Создатель-партнер)
 * @property Applicant $APPLICANT - Заявитель
 * @property ContactInfo $INSURANT
 * @property Prospect $PROSPECT
 *
 * @property ContainerCollection $LIST_OF_CONTEXTS
 */
class InputMessage extends Container
{
    protected $rules = [
        'GENERAL_QUOTE_INFO' => ['container:' . GeneralQuoteInfo::class],
        'SELLER' => ['container:' . Seller::class],
        'APPLICANT' => ['container:' . Applicant::class],
        'INSURANT' => ['container:' . ContactInfo::class],
        'PROSPECT' => ['container:' . Prospect::class],
        'UNDERWRITER' => ['container:' . Underwriter::class],
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