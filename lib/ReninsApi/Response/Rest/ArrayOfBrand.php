<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Array of brand
 *
 * @property ContainerCollection $Brand
 */
class ArrayOfBrand extends Container
{
    protected $rules = [
        'Brand' => ['containerCollection:' . Brand::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Brand = ContainerCollection::createFromXml($xml->Brand, Brand::class);
        return $this;
    }
}