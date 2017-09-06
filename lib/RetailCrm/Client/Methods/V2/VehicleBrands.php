<?php

namespace RetailCrm\Client\Methods\V2;

use RetailCrm\Request\Rest\VehicleBrandsAll;
use RetailCrm\Rest\Client as RestClient;

/**
 * Methods /Vehicle/Brands/*
 */
trait VehicleBrands
{
    /**
     * Method: /Vehicle/Brands/All
     * @param VehicleBrandsAll $params
     * @return \RetailCrm\Response\Rest\VehicleBrandsAll
     */
    public function vehicleBrandsAll(VehicleBrandsAll $params) {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $xml = $client->get('Vehicle/Brands/All', $params);
        return new \RetailCrm\Response\Rest\VehicleBrandsAll($xml);
    }
}