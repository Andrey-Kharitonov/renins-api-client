<?php

namespace ReninsApi\Request\Soap\Import;

/**
 * Информация по андерайтеру.
 *
 * @property string $WORK_PHONE - Рабочий телефон.
 * @property string $WORK_PHONE_EXT - Дополнительный номер к рабочему телефону.
 * @property string $EMAIL
 * @property string $BIRTH_DATE - Дата рождения андерайтера.
 */
class Underwriter extends Who
{
    protected function init()
    {
        $this->rules = array_merge($this->rules, [
            'WORK_PHONE' => ['toString'],
            'WORK_PHONE_EXT' => ['toString'],
            'EMAIL' => ['toString', 'email'],
            'BIRTH_DATE' => ['toString', 'date'],
        ]);
    }
}