<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Список водителей
 *
 * @property string $MULTI_DRIVE - Неограниченное количество водителей
 * @property string $LAST_DRIVER_ID
 * @property int $MIN_AGE - Минимальный возраст водителей, используется для Мультидрайв.
 * @property int $MIN_EXPERIENCE - Минимальный стаж водителей, используется для Мультидрайв.
 * @property string $STAFF - Штатные водители страхователя, используется для Мультидрайв.
 * @property ContainerCollection $DRIVER
 */
class Drivers extends Container
{
    protected $rules = [
        'MULTI_DRIVE' => ['toYN'],
        'LAST_DRIVER_ID' => ['toString'],
        'MIN_AGE' => ['toInteger', 'min:0'],
        'MIN_EXPERIENCE' => ['toInteger', 'min:0'],
        'STAFF' => ['toYN'],

        'DRIVER' => ['containerCollection:' . Driver::class],
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