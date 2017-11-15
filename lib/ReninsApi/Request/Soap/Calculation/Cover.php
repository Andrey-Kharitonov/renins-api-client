<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Риск/покрытие.
 *   UGON (Угон), значения > 0.
 *   USHERB (Ущерб), значения > 0.
 *   DO (ДО, Дополнительное Оборудование), интервал [0, 600000].
 *   NS (Несчастный Случай), интервал [0, 2500000].
 *   DAGO (ДАГО), фиксированные значения {0, 250000, 375000, 500000, 750000, 1000000, 1250000, 2500000}.
 *
 * @property string $code - Код риска/покрытия.
 * @property string $sum - Сумма страхования
 */
class Cover extends Container
{
    protected $rules = [
        'code' => ['toString', 'required', 'notEmpty'],
        'sum' => ['toDouble', 'required', 'min:0'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $xml->addAttribute('code', $this->code);
        $xml[0] = $this->sum;

        return $this;
    }
}