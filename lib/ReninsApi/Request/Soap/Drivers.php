<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Drivers
 *
 * @property int $type
 * @property string|bool $MultiDrive
 * @property int $MinAge
 * @property int $MinExperience
 * @property ContainerCollection $Driver
 */
class Drivers extends Container
{
    protected static $rules = [
        'type' => ['toInteger', 'in:1|2'],
        'MultiDrive' => ['toLogical', 'required'],
        'MinAge' => ['toInteger', 'min:0'],
        'MinExperience' => ['toInteger', 'min:0'],
        'Driver' => ['containerCollection'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $this->toXmlAttributes($xml, ['type', 'MultiDrive']);
        $this->toXmlTags($xml, ['MinAge', 'MinExperience']);

        if ($this->Driver !== null) {
            $this->Driver->toXml($xml, 'Driver');
        }

        return $this;
    }

    protected $type;
    protected $MultiDrive;
    protected $MinAge;
    protected $MinExperience;
    protected $Driver;
}