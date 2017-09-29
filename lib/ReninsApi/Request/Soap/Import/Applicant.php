<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Заявитель
 *
 * @property Who $WHO - Данные заявителя в именительном падеже.
 * @property Who $FROM - Данные заявителя в родительном падеже.
 */
class Applicant extends Container
{
    protected $rules = [
        'WHO' => ['container:' . Who::class],
        'FROM' => ['container:' . Who::class],
    ];
}