<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Данные о физ. лице
 *
 * @property string $IsIP - Флаг, является ли физ.лицо Индивидуальным Предпринимателем (ИП).
 * @property string $FirstName - Имя
 * @property string $MiddleName - Фамилия
 * @property string $LastName - Отчество
 * @property string $BirthDate - Дата рождения
 * @property string $Gender - Пол (М, Ж)
 * @property integer $MaritalStatus - Семейное положение (1 - Женат, 2 Не женат, 3 - Замужем, 4 - Не замужем)
 * @property string $HasChildren - Наличие несовершеннолетних детей
 * @property string $DriveExperience - Дата начала стажа.
 * @property ContainerCollection $Documents - Документы
 */
class Contact extends Container
{
    protected $rules = [
        'IsIP' => ['toLogical'],
        'FirstName' => ['toString', 'notEmpty'],
        'MiddleName' => ['toString', 'notEmpty'],
        'LastName' => ['toString', 'notEmpty'],
        'BirthDate' => ['toString', 'date'],
        'Gender' => ['toString', 'in:М|Ж|1|2'],
        'MaritalStatus' => ['toInteger', 'between:1|4'],
        'HasChildren' => ['toLogical'],
        'DriveExperience' => ['toString', 'date'],
        'Documents' => ['containerCollection:' . Document::class, 'length:|2'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['IsIP']);
        $this->toXmlTagsExcept($xml, ['IsIP']);
        return $this;
    }
}