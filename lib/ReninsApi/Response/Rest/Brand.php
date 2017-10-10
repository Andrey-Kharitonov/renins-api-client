<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Brand
 *
 * @property string $Name
 * @property ContainerCollection $Models
 * @property ContainerCollection $VehicleTypes
 */
class Brand extends Container
{
    protected $rules = [
        'Name' => ['toString'],
        'Models' => ['containerCollection:' . Model::class],
        'VehicleTypes' => ['containerCollection:' . VehicleType::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Name = (string) $xml->Name;
        if ($xml->Models) {
            $this->Models = ContainerCollection::createFromXml($xml->Models, '\ReninsApi\Response\Rest\Model');
        }
        if ($xml->VehicleTypes) {
            $this->VehicleTypes = ContainerCollection::createFromXml($xml->VehicleTypes, '\ReninsApi\Response\Rest\VehicleType');
        }

        return $this;
    }
}