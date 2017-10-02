<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные, котороеы заполняются в случае смены персональных данных юр. лица
 *
 * @property string $OrgName - Полное наименование организации
 * @property string $OrgINN - ИНН
 */
class PreviousDataJur extends Container
{
    protected $rules = [
        'OrgName' => ['toString', 'required', 'notEmpty'],
        'OrgINN' => ['toString', 'required', 'notEmpty'],
    ];
}