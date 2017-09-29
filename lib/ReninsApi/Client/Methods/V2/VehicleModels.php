<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Rest\ArrayOfModel;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Vehicle/Models/*
 */
trait VehicleModels
{
    /**
     * Method: /Vehicle/Models/BrandName={BrandName}
     * @param string $brand
     * @param bool $includeVehicleTypes
     * @return ArrayOfModel
     * @throws \Exception
     */
    public function vehicleModelsBrandName(string $brand, bool $includeVehicleTypes = false): ArrayOfModel {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = ['IncludeVehicleTypes' => ($includeVehicleTypes) ? '1' : ''];

        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Vehicle/Models/BrandName=' . urlencode($brand), $parameters);

            $res = ArrayOfModel::createFromXml($xml);
            $res->validateThrow();

            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
            ]);
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        } catch(\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
            ]);
            throw $exc;
        }

        return $res;
    }

}