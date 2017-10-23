<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Условия договора
 *
 * @property int $Product - Продукт.
 * @property string $ProgramType - Интеграционная программа, строковый код.
 * @property int $DurationMonth - Срок действия договора (срок страхования)
 *   Для ОСАГО:
 *   12-1 год
 *   11-11 мес
 *   10-10 мес
 *   9-9 мес
 *   8-8 мес
 *   7-7 мес
 *   6-6 мес
 *   5-5 мес
 *   4-4 мес
 *   3-3 мес
 *   2-2 мес
 *   1- до 1 мес
 *   0- до 20 дней транзит
 *   15- от 5 до 15 дней
 *   Для КАСКО значение может быть от 2 (2 месяца) до 62 (62 месяца).
 * @property int $PeriodUseMonth - Период использования, только для ОСАГО, значение может быть от 3 (3 месяца) до 12 (1 год).
 * @property ContainerCollection $Periods - Периоды использования
 * @property int $PaymentType - Тип рассрочки
 * @property string $Currency - default RUR. Валюта договора
 * @property string $Purpose - Цель использования ТС
 * @property string $DsasCoordinationId - Идентификатор согласования котировки ДСАС
 * @property string $CascoPolicyNumber - Номер оформленного полиса КАСКО
 * @property string $OsagoPolicyNumber - Номер оформленного полиса ОСАГО
 * @property string $OsagoDiscount - Скидка за Осаго
 * @property double $KV - Комиссионное вознаграждение
 */
class ContractTerm extends Container
{
    protected $rules = [
        'Product' => ['toInteger', 'required', 'between:1|6'],
        'ProgramType' => ['toString', 'notEmpty'],
        'DurationMonth' => ['toInteger', 'min:1'],
        'PeriodUseMonth' => ['toInteger', 'between:3|12'],
        'Periods' => ['containerCollection:' . Period::class],
        'PaymentType' => ['toInteger', 'min:1'],
        'Currency' => ['toString', 'required', 'currency'],
        'Purpose' => ['toString', 'purpose'],
        'DsasCoordinationId' => ['toString'],
        'CascoPolicyNumber' => ['toString'],
        'OsagoPolicyNumber' => ['toString'],
        'OsagoDiscount' => ['toLogical'],
        'KV' => ['toDouble'],
    ];

    protected function init()
    {
        $this->set('Currency', 'RUR');
    }
}