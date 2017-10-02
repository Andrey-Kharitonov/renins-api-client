<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 *
 *
 * @property string $OrgNameINNHash - Хеш (Полное наименование» (согласно Свидетельству о регистрации)+ «ИНН(для резидентов РФ))
 * @property string $OrgName - Полное наименование организации
 * @property string $OrgINN - ИНН
 * @property string $OPF - ОПФ
 * @property double $OrgKbm - Коэффициент бонус-малус для организации (при наличии справки)
 * @property PreviousDataJur $PreviousData - Заполнить в случае смены персональных данных
 */
class JuridicalPerson extends Container
{
    protected $rules = [
        'OrgNameINNHash' => ['toString', 'length:64|64'],
        'OrgName' => ['toString', 'required', 'notEmpty'],
        'OrgINN' => ['toString', 'required', 'notEmpty'],
        'OPF' => ['toString', 'required', 'notEmpty'],
        'OrgKbm' => ['toDouble', 'kbm'],
        'PreviousData' => ['container:' . PreviousDataJur::class],
    ];
}