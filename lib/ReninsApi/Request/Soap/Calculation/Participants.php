<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Участники договора
 *
 * @property Drivers $Drivers - Водители
 * @property Insurant $Insurant - Страхователь
 * @property int $BeneficiaryType - выгодоприобретатель физическое или юридическое лицо
 * @property Prospect $Prospect - Данные по проспекту, потенциальному клиенту, т.е. страхователю.
 * @property ContactInfo $Owner - Данные по владельцу ТС.
 * @property ContactInfo $Lessee - Данные по лизингополучателю, в настоящее время используется только для передачи признака ИП ФЛ либо ИНН ЮЛ.
 */
class Participants extends Container
{
    protected $rules = [
        'Drivers' => ['container:' . Drivers::class, 'required'],
        'Insurant' => ['container:' . Insurant::class, 'required'],
        'BeneficiaryType' => ['toInteger', 'participantType'],
        'Prospect' => ['container:' . Prospect::class],
        'Owner' => ['container:' . ContactInfo::class],
        'Lessee' => ['container:' . ContactInfo::class],
    ];
}