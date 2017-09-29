<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Период страхования
 *
 * @property string $TYPE - тип (персональная, безусловная, со второго страхового случая, виновника)
 * @property string $AGE - Возраст
 */
class Period extends Container
{
    protected $rules = [
        'USE_DATE_BEGIN' => ['toString', 'date'],
        'USE_DATE_END' => ['toString', 'date'],
    ];
}