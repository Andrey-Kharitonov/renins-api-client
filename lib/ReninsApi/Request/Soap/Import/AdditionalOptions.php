<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Дополнительные опции.
 *
 * @property string $AVARKOM_CALL - Условия вызова аваркома (на ДТП с двумя участниками или с пострадавшими по здоровью|а любое ДТП|на все события)
 * @property string $AVARKOM_REF - Сбор справок аварийным комиссаром (Y|N|на все ДТП|на ДТП с двумя участниками)
 * @property string $BROKEN_GLASS - Выплата без справок по стеклянным элементам (1 раз в год|без ограничений)
 * @property string $PAY_NOREF - Выплата без справок в процентах от страховой суммы (1 раз в год до 3%|2 раза в год до 5%)
 * @property string $ASSISTANCE - Техпомощь
 * @property string $UTS - Опция "Утрата товарной стоимости".
 * @property string $EVACUATION - Эвакуация
 * @property string $TAXI - Такси
 * @property string $LEASE - Аренда автомобиля.
 */
class AdditionalOptions extends Container
{
    protected $rules = [
        'AVARKOM_CALL' => ['toString', 'in:на ДТП с двумя участниками или с пострадавшими по здоровью|а любое ДТП|на все события'],
        'AVARKOM_REF' => ['toString', 'in:Y|N|на все ДТП|на ДТП с двумя участниками'],
        'BROKEN_GLASS' => ['toString', 'in:1 раз в год|без ограничений'],
        'PAY_NOREF' => ['toString', 'in:1 раз в год до 3%|2 раза в год до 5%'],
        'ASSISTANCE' => ['toYN'],
        'UTS' => ['toYN'],
        'EVACUATION' => ['toYN'],
        'TAXI' => ['toYN'],
        'LEASE' => ['toYN'],
    ];
}