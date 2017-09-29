<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Плановый платеж
 *
 * @property string $PAYMENT_DATE - Дата платежа
 * @property double $AMOUNT - Сумма платежа
 * @property integer $PERIOD_NUM - Номер оплачиваемого периода
 * @property string $PERIOD_END - Дата окончания оплачиваемого периода
 */
class Payment extends Container
{
    protected $rules = [
        'PAYMENT_DATE' => ['toString', 'date', 'required'],
        'AMOUNT' => ['toDouble', 'required', 'notEmpty'],
        'PERIOD_NUM' => ['toInteger', 'required', 'notEmpty'],
        'PERIOD_END' => ['toString', 'date'],
    ];
}