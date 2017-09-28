<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Способ возмещения.
 *
 * @property int $type
 *   1 - Выплата по калькуляции без учета износа.
 *   2 - Ремонт на СТОА по направлению Страховщика.
 *   3 - Ремонт на СТОА дилера по направлению Страховщика.
 *   4 - Ремонт на СТОА по выбору Страхователя.
 *   5 - Выплата по калькуляции с учетом износа частей, агрегатов.
 *   6 - GAP: ремонт стеклянных элементов на СТОА дилера.
 *   7 - Ремонт "стеклянных элементов" на СТОА (кроме дилеров) по направлению Страховщика.
 *   8 - Ремонт "стеклянных элементов" на любой СТОА по выбору Страхователя.
 * @property string $enabled - вкл/выкл. Например: type=3 по умолчанию включен и может понадобится его выключение
 */
class StoaType extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'notEmpty', 'between:1|8'],
        'enabled' => ['toLogical', 'required'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $xml->addAttribute('type', $this->type);
        $xml[0] = $this->enabled;

        return $this;
    }
}