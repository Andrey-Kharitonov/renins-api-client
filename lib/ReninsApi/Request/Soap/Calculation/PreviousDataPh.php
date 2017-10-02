<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные, котороеы заполняются в случае смены персональных данных физ. лица
 *
 * @property PersonDocument $PersonDocument - Вид, серия и номер документа, удостоверяющего личность
 * @property DriverDocument $DriverDocument - Серия и номер водительского удостоверения.
 * @property string $PersonSecondName - Ф
 * @property string $PersonFirstName - И
 * @property string $PersonSurName - О
 */
class PreviousDataPh extends Container
{
    protected $rules = [
        'PersonDocument' => ['container:' . PersonDocument::class],
        'DriverDocument' => ['container:' . DriverDocument::class],
        'PersonSecondName' => ['toString', 'required', 'notEmpty'],
        'PersonFirstName' => ['toString', 'required', 'notEmpty'],
        'PersonSurName' => ['toString', 'required', 'notEmpty'],
    ];
}