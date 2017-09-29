<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Текущий страховщик по договору страхования ТС (обязательно для GAP).
 *
 * @property string $CURRENT_INS_COMPANY_NAME - Наименование текущего страховщика (обязательно для GAP).
 * @property string $CURRENT_INS_POLICY_NUMBER - Номер текущего договора страхования ТС (обязательно для GAP).
 * @property string $CURRENT_INS_POLICY_DATE - Номер текущего договора (обязательно для GAP).
 */
class CurrentInsCompany extends Container
{
    protected $rules = [
        'CURRENT_INS_COMPANY_NAME' => ['toString'],
        'CURRENT_INS_POLICY_NUMBER' => ['toString'],
        'CURRENT_INS_POLICY_DATE' => ['toString'],
    ];
}