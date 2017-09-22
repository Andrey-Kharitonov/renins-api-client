<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Риск/покрытие.
 *
 * @property string $NAME - Название риска/покрытия (ГО, ДО, НС, Угон, Ущерб, ...)
 * @property string $BONUS - Премия по риску/покрытию.
 * @property string $INSURANCE_SUM - Страховая сумма по риску/покрытию.
 */
class RISK extends Container
{
    protected $rules = [
        'NAME' => ['toString', 'required', 'notEmpty'],
        'BONUS' => ['toString'], //Unknown type
        'INSURANCE_SUM' => ['toString'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributesExcept($xml, []); //all into attributes
        return $this;
    }

}