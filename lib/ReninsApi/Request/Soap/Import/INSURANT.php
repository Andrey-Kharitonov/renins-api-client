<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Страхователь
 * todo: доделать по xsd
 *
 * @property string $TYPE
 * @property CONTACT $CONTACT
 */
class INSURANT extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'personType'],

        'CONTACT' => ['container:' . CONTACT::class],
    ];
}