<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Серия и номер водительского удостоверения.
 *
 * @property string $Serial - серия
 * @property string $Number - номер
 */
class DriverDocument extends Container
{
    protected $rules = [
        'Serial' => ['toString', 'length:|20'],
        'Number' => ['toString', 'required', 'length:1|25'],
    ];
}