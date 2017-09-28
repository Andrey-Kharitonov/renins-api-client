<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные о юридическом лице.
 *
 * @property int $Name - Наименование
 * @property int $INN - ИНН
 */
class Account extends Container
{
    protected $rules = [
        'Name' => ['toString'],
        'INN' => ['toString'],
    ];
}