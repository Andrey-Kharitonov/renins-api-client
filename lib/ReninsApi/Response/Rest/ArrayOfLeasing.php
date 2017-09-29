<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Array of leasing
 *
 * @property ContainerCollection $Leasing
 */
class ArrayOfLeasing extends Container
{
    protected $rules = [
        'Leasing' => ['containerCollection:' . Leasing::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Leasing = ContainerCollection::createFromXml($xml, Leasing::class);
        return $this;
    }
}