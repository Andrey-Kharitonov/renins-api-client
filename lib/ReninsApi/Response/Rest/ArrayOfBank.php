<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Array of bank
 *
 * @property ContainerCollection $Bank
 */
class ArrayOfBank extends Container
{
    protected $rules = [
        'Bank' => ['containerCollection:' . Bank::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Bank = ContainerCollection::createFromXml($xml->Bank, Bank::class);
        return $this;
    }
}