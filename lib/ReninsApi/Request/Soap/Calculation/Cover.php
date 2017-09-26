<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Cover
 *
 * @property string $code
 * @property string $sum
 */
class Cover extends Container
{
    protected $rules = [
        'code' => ['toString', 'required', 'in:UGON|USHERB|DO|NS|DAGO'],
        'sum' => ['toDouble', 'required', 'notEmpty', 'min:0'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $xml->addAttribute('code', $this->code);
        $xml[0] = $this->sum;

        return $this;
    }
}