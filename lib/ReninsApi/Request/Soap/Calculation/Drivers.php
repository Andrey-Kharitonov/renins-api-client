<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Водители
 *
 * @property string $type - тип лиц, допущенных к управлению
 *   ""
 *   1 - сотрудники Страхователя, а также юридических лиц, указанных в Особых условиях страхования
 *   2 - любые лица
 * @property string $Multidrive - мультидрайв?
 * @property int $MinAge - минимальный возраст. Только для Multidrive = YES.
 * @property int $MinExperience - минимальный стаж.  Только для Multidrive = YES.
 * @property ContainerCollection $Driver - Список водителей
 */
class Drivers extends Container
{
    protected $rules = [
        'type' => ['toString', 'in:|1|2'],
        'Multidrive' => ['toLogical', 'required'],

        'MinAge' => ['toInteger', 'min:0'],
        'MinExperience' => ['toInteger', 'min:0'],
        'Driver' => ['containerCollection:' . Contact::class, 'length:|4'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['type', 'Multidrive']);
        $this->toXmlTags($xml, ['MinAge', 'MinExperience']);

        if ($this->Driver !== null) {
            $this->Driver->toXml($xml, 'Driver');
        }

        return $this;
    }
}