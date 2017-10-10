<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Контекст
 * todo: доделать по xsd
 *
 * @property PrivateQuoteInfo $PRIVATE_QUOTE_INFO
 * @property Vehicle $VEHICLE
 * @property Owner $OWNER
 * @property Drivers $DRIVERS
 * @property Beneficiaries $BENEFICIARIES
 */
class Context extends Container
{
    protected $rules = [
        'PRIVATE_QUOTE_INFO' => ['container:' . PrivateQuoteInfo::class],
        'VEHICLE' => ['container:' . Vehicle::class],
        'OWNER' => ['container:' . Owner::class],
        'DRIVERS' => ['container:' . Drivers::class],
        'BENEFICIARIES' => ['container:' . Beneficiaries::class],
    ];
}