<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Адрес
 *
 * @property string $TYPE - тип адреса
 * @property string $COUNTRY - Страна
 * @property string $PROVINCE - Область/Республика/Край
 * @property string $REGION - Район
 * @property string $CITY - Город
 * @property string $STREET - Улица. Для военнослужащих указывать номер воинской части.
 * @property string $HOUSE - Дом. Для военнослужащих указывать прочерк.
 * @property string $HOUSE_EXT - Корпус/Строение.
 * @property string $APPARTMENT - Квартира/Офис.
 * @property string $UNDERGROUND_STATION - Станция метро (ближайшая).
 * @property string $POSTAL_CODE - Индекс.
 * @property string $KLADR - Код КЛАДР.
 */
class Address extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'in:ADDR_ACNT_JUR|ADDR_ACNT_POST|ADDR_CON_REG|ADDR_CON_FACT|ADDR_CON_POST'],

        'COUNTRY' => ['toString'],
        'PROVINCE' => ['toString'],
        'REGION' => ['toString'],
        'CITY' => ['toString'],
        'STREET' => ['toString'],
        'HOUSE' => ['toString'],
        'HOUSE_EXT' => ['toString'],
        'APPARTMENT' => ['toString'],
        'UNDERGROUND_STATION' => ['toString'],
        'POSTAL_CODE' => ['toString'],
        'KLADR' => ['toString'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE']);
        $this->toXmlTagsExcept($xml, ['TYPE']);
        return $this;
    }
}