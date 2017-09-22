<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Документ
 * todo: доделать по xsd
 *
 * @property string $TYPE - тип документа
 * @property string $SERIES - Серия
 * @property string $NUMBER - Номер
 * @property string $ISSUED_WHERE - Где/Кем выдано
 * @property string $ISSUED_DATE - Когда выдано
 */
class DOCUMENT extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'docType:import'],
        'SERIES' => ['toString'],
        'NUMBER' => ['toString'],
        'ISSUED_WHERE' => ['toString'],
        'ISSUED_DATE' => ['toString', 'date'],
    ];
}
