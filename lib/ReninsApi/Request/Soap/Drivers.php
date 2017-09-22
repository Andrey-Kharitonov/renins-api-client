<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Drivers
 *
 * @property int $type
 * @property string $Multidrive
 * @property int $MinAge
 * @property int $MinExperience
 * @property ContainerCollection $Driver
 */
class Drivers extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'in:1|2'],
        'Multidrive' => ['toLogical', 'required'],
        'MinAge' => ['toInteger', 'min:0'],
        'MinExperience' => ['toInteger', 'min:0'],
        'Driver' => ['containerCollection:' . Driver::class],
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