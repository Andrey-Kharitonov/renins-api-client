<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Адрес
 * todo: доделать по xsd
 *
 * @property string $TYPE - тип адреса
 * @property string $COUNTRY - Страна
 * @property string $CITY - Город
 * @property string $STREET - Улица. Для военнослужащих указывать номер воинской части.
 * @property string $HOUSE - Дом. Для военнослужащих указывать прочерк.
 * @property string $APPARTMENT - Квартира/Офис.
 */
class ADDRESS extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'in:ADDR_ACNT_JUR|ADDR_ACNT_POST|ADDR_CON_REG|ADDR_CON_FACT|ADDR_CON_POST'],
        'COUNTRY' => ['toString'],
        'CITY' => ['toString'],
        'STREET' => ['toString'],
        'HOUSE' => ['toString'],
        'APPARTMENT' => ['toString'],
    ];
}