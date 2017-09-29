<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Способы возмещения.
 *
 * @property string $CALC_PAYMENT_WO_WEAR - Выплата по калькуляции без учета износа.
 * @property string $CALC_PAYMENT_WITH_WEAR - Выплата по калькуляции с учетом износа частей, агрегатов
 * @property string $NONDILERS_STOA_BY_INSURER_ORDER - Ремонт на СТОА (кроме дилеров) по направлению Страховщика.
 * @property string $DILERS_STOA_BY_INSURER_ORDER - Ремонт на СТОА дилера по направлению Страховщика.
 * @property string $STOA_BY_INSURANT_SELECTION - Ремонт на СТОА по выбору Страхователя.
 * @property string $REPAIR_GLASS_ON_DILERS_STOA - Ремонт стеклянных элементов на СТОА дилера.
 * @property string $REPAIR_GLASS_ON_NOTDILERS_STOA - Ремонт стеклянных элементов на СТОА (кроме дилеров) по направлению Страховщика.
 * @property string $REPAIR_GLASS_ON_ANY_STOA - Ремонт стеклянных элементов на любой СТОА по выбору Страхователя.
 */
class ModesOfCompensation extends Container
{
    protected $rules = [
        'CALC_PAYMENT_WO_WEAR' => ['toYN'],
        'CALC_PAYMENT_WITH_WEAR' => ['toYN'],
        'NONDILERS_STOA_BY_INSURER_ORDER' => ['toYN'],
        'DILERS_STOA_BY_INSURER_ORDER' => ['toYN'],
        'STOA_BY_INSURANT_SELECTION' => ['toYN'],
        'REPAIR_GLASS_ON_DILERS_STOA' => ['toYN'],
        'REPAIR_GLASS_ON_NOTDILERS_STOA' => ['toYN'],
        'REPAIR_GLASS_ON_ANY_STOA' => ['toYN'],
    ];
}