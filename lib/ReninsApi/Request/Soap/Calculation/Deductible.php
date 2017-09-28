<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Deductible
 *
 * @property int $type - тип (1 - Безусловная, 2 - Безусловная со второго страхового случая, 3 - Персональная, 4 - Франшиза виновника)
 * @property int $sum - Величина франшизы (0, 5000, 7500, 10000, 12500, 15000, 17500, 20000, 22500, 25000, 30000, 37500, 50000)
 */
class Deductible extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'notEmpty', 'between:1|4'],
        'sum' => ['toDouble', 'required', 'min:0'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $xml->addAttribute('type', $this->type);
        $xml[0] = $this->sum;

        return $this;
    }

    public function fromXml(\SimpleXMLElement $xml) {
        $this->fromXmlAttributes($xml, ['type']);
        $this->sum = (string) $xml;
        return $this;
    }
}