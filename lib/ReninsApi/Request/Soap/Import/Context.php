<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Контекст
 *
 * @property PrivateQuoteInfo $PRIVATE_QUOTE_INFO - Дополнительная информация по котировке (уникальная для каждого объекта страхования)
 * @property Vehicle $VEHICLE - Информация по объекту страхования (транспортное средство).
 * @property Owner $OWNER - Владелец ТС.
 * @property Drivers $DRIVERS - Водители
 * @property Beneficiaries $BENEFICIARIES - Выгодоприобретатели
 * @property Lessee $LESSEE
 */
class Context extends Container
{
    protected $rules = [
        'PRIVATE_QUOTE_INFO' => ['container:' . PrivateQuoteInfo::class],
        'VEHICLE' => ['container:' . Vehicle::class],
        'OWNER' => ['container:' . Owner::class],
        'DRIVERS' => ['container:' . Drivers::class],
        'BENEFICIARIES' => ['container:' . Beneficiaries::class],
        'LESSEE' => ['container:' . Lessee::class],
    ];
}