<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\Rest\VehicleBrandsAll;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Vehicle/Brands/*
 */
trait VehicleBrands
{
    /**
     * Method: /Vehicle/Brands/All
     * @param null|string $VehicleType
     * @return \ReninsApi\Response\Rest\VehicleBrandsAll
     * @internal param VehicleBrandsAll $params
     */
    public function vehicleBrandsAll($VehicleType = null) {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $xml = $client->get('Vehicle/Brands/All', ['VehicleType' => $VehicleType]);
        return new \ReninsApi\Response\Rest\VehicleBrandsAll($xml);
    }
}