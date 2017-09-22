<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * Пролонгационные параметры.
 *
 * @property string $CELL_CC_FLAG - Флаг Продажа КЦ
 * @property string $INFO_CALL_FLAG - Флаг Уведомительный звонок
 * @property string $SMS_FLAG - Флаг SMS
 * @property string $EMAIL_WITH_COST_FLAG - Флаг Email со стоимостью
 * @property string $EMAIL_NO_COST_FLAG - Флаг Email без стоимости
 * @property string $DMAIL_WITH_COST_FLAG - Флаг Письмо (direct mail) со стоимостью
 * @property string $DMAIL_NO_COST_FLAG - Флаг Письмо (direct mail) без стоимости
 */
class PROLONGATION_PARAMS extends Container
{
    protected $rules = [
        'CELL_CC_FLAG' => ['toYN'],
        'INFO_CALL_FLAG' => ['toYN'],
        'SMS_FLAG' => ['toYN'],
        'EMAIL_WITH_COST_FLAG' => ['toYN'],
        'EMAIL_NO_COST_FLAG' => ['toYN'],
        'DMAIL_WITH_COST_FLAG' => ['toYN'],
        'DMAIL_NO_COST_FLAG' => ['toYN'],
    ];
}