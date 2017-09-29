<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * ФИО
 *
 * @property string $LAST_NAME - имя
 * @property string $FIRST_NAME - фамилия
 * @property string $MIDDLE_NAME - отчество
 */
class Who extends Container
{
    protected $rules = [
        'LAST_NAME' => ['toString'],
        'FIRST_NAME' => ['toString'],
        'MIDDLE_NAME' => ['toString'],
    ];
}