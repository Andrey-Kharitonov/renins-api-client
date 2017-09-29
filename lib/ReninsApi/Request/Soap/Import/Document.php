<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Документ
 *
 * @property string $TYPE - тип документа
 * @property string $SERIES - Серия
 * @property string $NUMBER - Номер
 * @property string $ISSUED_WHERE - Где/Кем выдано
 * @property string $ISSUED_DATE - Когда выдано
 * @property string $EXPIRE_DATE - Дата окончания срока действия документа.
 * @property string $LD_CATEGORY_A - Наличие категории A в водительском удостоверении
 * @property string $LD_CATEGORY_B - Наличие категории B в водительском удостоверении
 * @property string $LD_CATEGORY_C - Наличие категории C в водительском удостоверении
 * @property string $LD_CATEGORY_D - Наличие категории D в водительском удостоверении
 * @property string $LD_CATEGORY_E - Наличие категории E в водительском удостоверении
 */
class Document extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'docType:import'],

        'SERIES' => ['toString'],
        'NUMBER' => ['toString'],
        'ISSUED_WHERE' => ['toString'],
        'ISSUED_DATE' => ['toString', 'date'],
        'EXPIRE_DATE' => ['toString', 'date'],
        'LD_CATEGORY_A' => ['toYN'],
        'LD_CATEGORY_B' => ['toYN'],
        'LD_CATEGORY_C' => ['toYN'],
        'LD_CATEGORY_D' => ['toYN'],
        'LD_CATEGORY_E' => ['toYN'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE']);
        $this->toXmlTagsExcept($xml, ['TYPE']);
        return $this;
    }

}
