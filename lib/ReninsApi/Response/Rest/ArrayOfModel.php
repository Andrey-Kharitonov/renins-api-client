<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Array of model
 *
 * @property ContainerCollection $Model
 */
class ArrayOfModel extends Container
{
    protected $rules = [
        'Model' => ['containerCollection:' . ModelExt::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Model = ContainerCollection::createFromXml($xml->Model, ModelExt::class);
        return $this;
    }
}