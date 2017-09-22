<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Дополнительное оборудование.
 *
 * @property string $TYPE - Тип дополнительного оборудования.
 * @property string $MARK - Марка дополнительного оборудования.
 * @property string $MODEL - Модель дополнительного оборудования.
 * @property double $COST - Стоимость дополнительного оборудования.
 * @property int $AMOUNT - Количество единиц дополнительного оборудования.
 * @property string $UNIT - Комплектация дополнительного оборудования.
 */
class EQUIPMENT extends Container
{
    protected $rules = [
        'TYPE' => ['toString'],
        'MARK' => ['toString'],
        'MODEL' => ['toString'],
        'COST' => ['toDouble'],
        'AMOUNT' => ['toInteger'],
        'UNIT' => ['toString'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributesExcept($xml, []);
        return $this;
    }
}