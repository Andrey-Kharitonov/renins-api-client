<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные о физическом или юридическом лице.
 *
 * @property int $type - Физ. или юр. лицо (1 - физ. лицо, 2 - юр. лицо)
 * @property Contact $Contact - Физ. лицо
 * @property Account $Account - Юр. лицо
 */
abstract class ContactInfo extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'participantType'],
        'Contact' => ['container:' . Contact::class],
        'Account' => ['container:' . Account::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['type']);
        $this->toXmlTagsExcept($xml, ['type']);
        return $this;
    }
}