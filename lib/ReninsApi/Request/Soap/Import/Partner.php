<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Создатель-партнер.
 * todo: доделать по xsd
 *
 * @property string $NAME - Наименование
 * @property string $DEPARTMENT - Наименование Департамента
 * @property string $DIVISION - Дивизион
 * @property string $FILIAL - Филиал
 */
class Partner extends Container
{
    protected $rules = [
        'NAME' => ['toString'],
        'DEPARTMENT' => ['toString'],
        'DIVISION' => ['toString'],
        'FILIAL' => ['toString'],
    ];
}