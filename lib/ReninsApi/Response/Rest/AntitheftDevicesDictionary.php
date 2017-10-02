<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Antitheft devices dictionary
 *
 * @property ContainerCollection $AlarmSystem
 */
class AntitheftDevicesDictionary extends Container
{
    protected $rules = [
        'AlarmSystem' => ['containerCollection:' . AlarmSystem::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->AlarmSystem = ContainerCollection::createFromXml($xml->AlarmSystem, AlarmSystem::class);
        return $this;
    }
}