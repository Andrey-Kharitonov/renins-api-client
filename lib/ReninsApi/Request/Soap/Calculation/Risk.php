<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Риск для пролонгации
 *
 * @property string $Risks - Название риска
 * @property string $Bonus - Премия по риску
 * @property string $InsuranceSum - Страховая сумма по риску
 */
class Risk extends Container
{
    protected $rules = [
        'NAME' => ['toString', 'required', 'in:ГО|ДО|НС|Угон|Ущерб|Домашнее имущество|ОСАГО|Конструктивные элементы|Внутренняя отделка|ДР|ФД|DI_ДР|GAP'],
        'Bonus' => ['toString'], //unknown type
        'InsuranceSum' => ['toString'], //unknown type
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributesExcept($xml, []);

        return $this;
    }
}