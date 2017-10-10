<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Предстраховой осмотр.
 *
 * @property string $NEW_OBJECT - Новое ТС / Новый объект страхования
 * @property string $INSPECTION_IS_NEEDED - Требуется прдварительный осмотр
 * @property string $INSPECTION_NOT_NEEDED_OLD_OBJECT - Предварительный осмотр не требуется, ТС не новое (используется только при пролонгации).
 */
class PreInsuranceInspection extends Container
{
    protected $rules = [
        'NEW_OBJECT' => ['toYN'],
        'INSPECTION_IS_NEEDED' => ['toYN'],
        'INSPECTION_NOT_NEEDED_OLD_OBJECT' => ['toYN'],
    ];
}