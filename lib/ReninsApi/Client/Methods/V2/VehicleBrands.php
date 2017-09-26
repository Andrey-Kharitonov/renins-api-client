<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Response\Rest\ArrayOfBrand;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Vehicle/Brands/*
 */
trait VehicleBrands
{
    /**
     * Method: /Vehicle/Brands/All
     * @param null|string $VehicleType
     * @return ArrayOfBrand
     * @throws \Exception
     */
    public function vehicleBrandsAll($VehicleType = null): ArrayOfBrand {
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

        return ArrayOfBrand::createFromXml($xml);
    }

    /**
     * Method: /Vehicle/Brands/AllWithModels
     * @param null|string $VehicleType
     * @return ArrayOfBrand
     * @throws \Exception
     */
    public function vehicleBrandsAllWithModels($VehicleType = null): ArrayOfBrand {
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

        return ArrayOfBrand::createFromXml($xml);
    }
}