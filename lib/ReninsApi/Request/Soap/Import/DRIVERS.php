<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Список водителей
 * todo: доделать по xsd
 *
 * @property string $MULTI_DRIVE - Неограниченное количество водителей
 * @property int $MIN_AGE - Минимальный возраст водителей, используется для Мультидрайв.
 * @property int $MIN_EXPERIENCE - Минимальный стаж водителей, используется для Мультидрайв.
 * @property string $STAFF - Штатные водители страхователя, используется для Мультидрайв.
 * @property ContainerCollection $DRIVER
 */
class DRIVERS extends Container
{
    protected $rules = [
        'MULTI_DRIVE' => ['toYN'],
        'MIN_AGE' => ['toInteger', 'min:0'],
        'MIN_EXPERIENCE' => ['toInteger', 'min:0'],
        'STAFF' => ['toYN'],

        'DRIVER' => ['containerCollection:' . DRIVER::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['MULTI_DRIVE', 'MIN_AGE', 'MIN_EXPERIENCE', 'STAFF']);

        //BENEFICIARY into here
        $value = $this->DRIVER;
        if ($value !== null) {
            $value->toXml($xml, 'DRIVER');
        }

        return $this;
    }
}