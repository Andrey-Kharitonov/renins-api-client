<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Физическое лицо
 * todo: доделать по xsd
 *
 * @property string $IPFLAG - Признак, является ли физ.лицо Индивидуальным Предпринимателем (ИП).
 * @property string $LAST_NAME - Фамилия
 * @property string $FIRST_NAME - Имя
 * @property string $MIDDLE_NAME - Отчество
 * @property string $BIRTH_DATE - Дата рождения
 * @property string $HOME_PHONE - Домашний телефон.
 * @property string $CELL_PHONE - Мобильный телефон.
 * @property string $RESIDENT - Резидент.
 * @property string $DRIVE_EXPERIENCE - Дата начала стажа
 * @property ContainerCollection $CONTACT_ADDRESSES
 * @property ContainerCollection $CONTACT_DOCUMENTS
 */
class Contact extends Container
{
    protected $rules = [
        'IPFLAG' => ['toYN'],

        'LAST_NAME' => ['toString'],
        'FIRST_NAME' => ['toString'],
        'MIDDLE_NAME' => ['toString'],
        'BIRTH_DATE' => ['toString', 'date'],
        'HOME_PHONE' => ['toString'],
        'CELL_PHONE' => ['toString'],
        'RESIDENT' => ['toYN'],
        'DRIVE_EXPERIENCE' => ['toString', 'date'],
        'CONTACT_ADDRESSES' => ['containerCollection:' . Address::class],
        'CONTACT_DOCUMENTS' => ['containerCollection:' . Document::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['IPFLAG']);
        $this->toXmlTagsExcept($xml, ['IPFLAG', 'CONTACT_ADDRESSES', 'CONTACT_DOCUMENTS']);

        if ($this->CONTACT_ADDRESSES) {
            $added = $xml->addChild('CONTACT_ADDRESSES');
            $this->CONTACT_ADDRESSES->toXml($added, 'ADDRESS');
        }

        if ($this->CONTACT_DOCUMENTS) {
            $added = $xml->addChild('CONTACT_DOCUMENTS');
            $this->CONTACT_DOCUMENTS->toXml($added, 'DOCUMENT');
        }

        return $this;
    }
}