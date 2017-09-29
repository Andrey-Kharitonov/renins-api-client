<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Информация о юр. лице
 *
 * @property Document $DOCUMENT
 * @property string $NAME - Наименование
 * @property string $OPF - Организационно-правовая форма.
 * @property string $PRINT_NAME - Наименование для печати.
 * @property string $FTN - ИНН
 * @property string $OGRN - ОГРН.
 * @property string $KPP - КПП.
 * @property string $EMAIL - Email
 * @property string $MAIN_PHONE - Основной телефон.
 * @property string $SECOND_PHONE - Второй телефон.
 * @property string $RESIDENT - Резидент?
 * @property int $COUNTRY_ID - Цифровой код страны.
 * @property string $NAME_ENU - Наименование на латинице.
 * @property string $BIK - БИК.
 * @property string $RS - Р/С.
 * @property ContainerCollection $ACCOUNT_ADDRESSES
 */
class Account extends Container
{
    protected $rules = [
        'DOCUMENT' => ['container:' . Document::class],
        'NAME' => ['toString'],
        'OPF' => ['toString'],
        'PRINT_NAME' => ['toString'],
        'FTN' => ['toString'],
        'OGRN' => ['toString'],
        'KPP' => ['toString'],
        'EMAIL' => ['toString'],
        'MAIN_PHONE' => ['toString'],
        'SECOND_PHONE' => ['toString'],
        'RESIDENT' => ['toYN'],
        'COUNTRY_ID' => ['toInteger'],
        'NAME_ENU' => ['toString'],
        'BIK' => ['toString'],
        'RS' => ['toString'],
        'ACCOUNT_ADDRESSES' => ['container:' . Address::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlTagsExcept($xml, ['ACCOUNT_ADDRESSES']);

        if ($this->ACCOUNT_ADDRESSES) {
            $added = $xml->addChild('ACCOUNT_ADDRESSES');
            $this->ACCOUNT_ADDRESSES->toXml($xml, 'ADDRESS');
        }

        return $this;
    }
}