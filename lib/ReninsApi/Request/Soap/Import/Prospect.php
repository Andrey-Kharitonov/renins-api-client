<?php

namespace ReninsApi\Request\Soap\Import;

/**
 * Контактное лицо.
 *
 * @property string $PHONE - Контактный телефон.
 * @property string $EMAIL
 */
class Prospect extends Who
{
    protected function init()
    {
        $this->rules = array_merge($this->rules, [
            'PHONE' => ['toString'],
            'EMAIL' => ['toString', 'email'],
        ]);
    }
}