<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные о физ. лице
 *
 * @property string $type - Тип физического лица - ЛДУ или собственник (driver - водитель, owner - собственник)
 * @property PersonDocument $PersonDocument - Вид, серия и номер документа, удостоверяющего личность
 * @property DriverDocument $DriverDocument - Серия и номер водительского удостоверения.
 * @property string $PersonNameBirthHash - Хеш ФИО+даты рождения
 * @property string $PersonSecondName - Ф
 * @property string $PersonFirstName - И
 * @property string $PersonSurName - О
 * @property string $PersonBirthDate - ДР
 * @property string $DriverExperienceDate - Дата начала стажа
 * @property double $DriverKbm - Коэффициент бонус-малус для водителя (при наличии справки)
 * @property double $PersonKbm - Коэффициент бонус-малус для собственника (при наличии справки)
 * @property PreviousDataPh $PreviousData - Заполнить в случае смены персональных данных
 */
class PhysicalPerson extends Container
{
    protected $rules = [
        'type' => ['toString', 'in:driver|owner'],

        'PersonDocument' => ['container:' . PersonDocument::class],
        'DriverDocument' => ['container:' . DriverDocument::class],
        'PersonNameBirthHash' => ['toString', 'length:64|64'],
        'PersonSecondName' => ['toString', 'required', 'notEmpty'],
        'PersonFirstName' => ['toString', 'required', 'notEmpty'],
        'PersonSurName' => ['toString', 'required', 'notEmpty'],
        'PersonBirthDate' => ['toString', 'required', 'date'],
        'DriverExperienceDate' => ['toString', 'required', 'date'],
        'DriverKbm' => ['toDouble', 'kbm'],
        'PersonKbm' => ['toDouble', 'kbm'],
        'PreviousData' => ['container:' . PreviousDataPh::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['type']);
        $this->toXmlTagsExcept($xml, ['type']);

        return $this;
    }
}