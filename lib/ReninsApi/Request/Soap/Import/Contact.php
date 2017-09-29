<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Физическое лицо
 *
 * @property string $IPFLAG - Признак, является ли физ.лицо Индивидуальным Предпринимателем (ИП).
 * @property string $LAST_NAME - Фамилия
 * @property string $FIRST_NAME - Имя
 * @property string $MIDDLE_NAME - Отчество
 * @property string $BIRTH_DATE - Дата рождения
 * @property string $GENDER - Пол (М, Ж)
 * @property string $CELL_PHONE - Мобильный телефон.
 * @property string $HOME_PHONE - Домашний телефон.
 * @property string $WORK_PHONE - Рабочий телефон.
 * @property string $WORK_PHONE_EXT - Дополнительный код рабочего телефона.
 * @property string $EMAIL - Email
 * @property string $RESIDENT - Резидент.
 * @property integer $COUNTRY_ID - Цифровой код страны.
 * @property string $DRIVE_EXPERIENCE - Дата начала стажа
 * @property ContainerCollection $CONTACT_ADDRESSES
 * @property ContainerCollection $CONTACT_DOCUMENTS
 * @property string $BLACKLISTFLAG - В черном списке
 * @property string $OGRN - ОГРН (ФЛ+ИП)
 * @property string $INN - ИНН (ФЛ+ИП)
 * @property string $MARITAL_STATUS - Семейное положение (1 - Женат, 2 Не женат, 3 - Замужем, 4 - Не замужем)
 * @property string $HAS_CHILDREN - Наличие несовершеннолетних детей.
 */
class Contact extends Container
{
    protected $rules = [
        'IPFLAG' => ['toYN'],

        'LAST_NAME' => ['toString'],
        'FIRST_NAME' => ['toString'],
        'MIDDLE_NAME' => ['toString'],
        'BIRTH_DATE' => ['toString', 'date'],
        'GENDER' => ['toString', 'gender'],
        'CELL_PHONE' => ['toString'],
        'HOME_PHONE' => ['toString'],
        'WORK_PHONE' => ['toString'],
        'WORK_PHONE_EXT' => ['toString'],
        'EMAIL' => ['toString', 'email'],
        'RESIDENT' => ['toYN'],
        'COUNTRY_ID' => ['toInteger'],
        'DRIVE_EXPERIENCE' => ['toString', 'date'],
        'CONTACT_ADDRESSES' => ['containerCollection:' . Address::class],
        'CONTACT_DOCUMENTS' => ['containerCollection:' . Document::class],
        'BLACKLISTFLAG' => ['toYN'],
        'OGRN' => ['toString'], //Unknown type
        'INN' => ['toString'], //Unknown type
        'MARITAL_STATUS' => ['toString', 'between:1|4'],
        'HAS_CHILDREN' => ['toYN'],
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