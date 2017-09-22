<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * General info
 *
 * @property string $PROLONGATION_PREV_NUMBER - Предыдущий номер полиса (для пролонгации)
 * @property string $ACCOUNT_NUMBER_CALCBASED_ON - Номер котировки/расчета (используется при интеграции партнёров)
 * @property string $DSAS_COORDINATION_ID - Идентификатор согласования котировки ДСАС
 * @property string $PACKET_CALCBASED_ON - Наименование (название/номер) пакета выбираемого из результатов расчёта и используемого для дальнейшего оформления.
 * @property string $TYPE - Тип котировки
 * @property double $COMMISSION - КВ для данной котировки
 * @property string $SALE_DATE - Дата продажи полиса, должна быть в интервале текущей даты до даты начала действия полиса.
 * @property string $INSURANCE_SUM - Сумма расчета.
 * @property string $CURRENCY - Валюта расчета.
 * @property string $PROLONGATION_PARAMS - Пролонгационные параметры.
 */
class GENERAL_QUOTE_INFO extends Container
{
    protected $rules = [
        'PROLONGATION_PREV_NUMBER' => ['toString'],
        'ACCOUNT_NUMBER_CALCBASED_ON' => ['toString'],
        'DSAS_COORDINATION_ID' => ['toString'],
        'PACKET_CALCBASED_ON' => ['toString'],
        'TYPE' => ['toString', 'in:Розничное страхование авто|Коммерческое страхование авто'],
        'COMMISSION' => ['toDouble'],
        'SALE_DATE' => ['toString', 'date'],
        'INSURANCE_SUM' => ['toString'],
        'CURRENCY' => ['toString', 'currency'],
        'PROLONGATION_PARAMS' => ['container:' . PROLONGATION_PARAMS::class],
    ];
}