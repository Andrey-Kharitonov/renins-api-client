<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Информация о юр. или физ. лице
 *
 * @property string $TYPE
 * @property Contact $CONTACT
 * @property Account $ACCOUNT
 */
class ContactInfo extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'personType'],

        'CONTACT' => ['container:' . Contact::class],
        'ACCOUNT' => ['container:' . Account::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE']);
        $this->toXmlTagsExcept($xml, ['TYPE']);
        return $this;
    }

    public function validate()
    {
        $errors = parent::validate();

        if ($this->TYPE == 'CONTACT'
            && !$this->CONTACT) {
            $errors['CONTACT'][] = "CONTACT is required for TYPE == CONTACT";
        } elseif ($this->TYPE == 'ACCOUNT'
            && !$this->ACCOUNT) {
            $errors['ACCOUNT'][] = "ACCOUNT is required for TYPE == ACCOUNT";
        }

        return $errors;
    }
}