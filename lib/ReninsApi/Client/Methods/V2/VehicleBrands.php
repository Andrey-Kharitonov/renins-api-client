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
     * @throws \Exception
     */
    public function vehicleBrandsAll($VehicleType = null) {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = ['VehicleType' => $VehicleType];
        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Vehicle/Brands/All', $parameters);
            $this->logMessage(__METHOD__, 'Successful', ['xml' => $xml]);
        } catch(\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), $parameters);
            throw $exc;
        }

        return new \ReninsApi\Response\Rest\VehicleBrandsAll($xml);
    }

    /**
     * Method: /Vehicle/Brands/AllWithModels
     * @param null|string $VehicleType
     * @return \ReninsApi\Response\Rest\VehicleBrandsAll
     * @throws \Exception
     */
    public function vehicleBrandsAllWithModels($VehicleType = null) {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = ['VehicleType' => $VehicleType];
        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Vehicle/Brands/AllWithModels', $parameters);
            $this->logMessage(__METHOD__, 'Successful', ['xml' => $xml]);
        } catch(\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), $parameters);
            throw $exc;
        }

        return new \ReninsApi\Response\Rest\VehicleBrandsAll($xml);
    }
}