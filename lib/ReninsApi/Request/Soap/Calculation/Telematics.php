<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Данные по телематике.
 *
 * @property string $Enabled - Признак подключения телематики.
 * @property int $Points - Начисленный балл, значение от 0 до 100 (целые числа), отсутствие элемента соответствует отсутствию информации.
 * @property int $LossCount - Количество убытков, значение 6 соответствует варианту «6 и более» в выпадающем списке в системе B2B, отсутствие элемента соответствует варианту «Нет информации».
 */
class Telematics extends Container
{
    protected $rules = [
        'Enabled' => ['toLogical', 'required'],
        'Points' => ['toInteger', 'between:0|100'],
        'LossCount' => ['toInteger', 'between:0|6'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['Enabled']);
        $this->toXmlTags($xml, ['Points', 'LossCount']);

        return $this;
    }
}