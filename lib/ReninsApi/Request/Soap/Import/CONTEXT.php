<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Контекст
 * todo: доделать по xsd
 *
 * @property PRIVATE_QUOTE_INFO $PRIVATE_QUOTE_INFO
 * @property VEHICLE $VEHICLE
 * @property OWNER $OWNER
 * @property DRIVERS $DRIVERS
 * @property BENEFICIARIES $BENEFICIARIES
 */
class CONTEXT extends Container
{
    protected $rules = [
        'PRIVATE_QUOTE_INFO' => ['container:' . PRIVATE_QUOTE_INFO::class],
        'VEHICLE' => ['container:' . VEHICLE::class],
        'OWNER' => ['container:' . OWNER::class],
        'DRIVERS' => ['container:' . DRIVERS::class],
        'BENEFICIARIES' => ['container:' . BENEFICIARIES::class],
    ];
}