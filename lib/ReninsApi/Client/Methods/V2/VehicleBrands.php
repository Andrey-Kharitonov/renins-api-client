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
     * @param VehicleBrandsAll $params
     * @return \ReninsApi\Response\Rest\VehicleBrandsAll
     */
    public function vehicleBrandsAll(VehicleBrandsAll $params) {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $xml = $client->get('Vehicle/Brands/All', $params);
        return new \ReninsApi\Response\Rest\VehicleBrandsAll($xml);
    }
}