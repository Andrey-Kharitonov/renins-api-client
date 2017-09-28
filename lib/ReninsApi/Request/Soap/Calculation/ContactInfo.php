<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные о физическом или юридическом лице.
 * Если указан type = 1, то обязателен блок Contact
 *             type = 2, то обязателен блок Account
 *
 * @property int $type - Физ. или юр. лицо
 * @property Contact $Contact - Физ. лицо
 * @property Account $Account - Юр. лицо
 */
class ContactInfo extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'participantType'],
        'Contact' => ['container:' . Contact::class],
        'Account' => ['container:' . Account::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->fromXmlAttributes($xml, ['type']);
        $this->toXmlTagsExcept($xml, ['type']);
        return $this;
    }

    public function validate()
    {
        $errors = parent::validate();

        if ($this->type == 1
            && !$this->Contact) {
            $errors['Contact'][] = "Contact is required for type == 1";
        } elseif ($this->type == 2
            && !$this->Account) {
            $errors['Account'][] = "Account is required for type == 2";
        }

        return $errors;
    }
}