<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\Rest\VehicleBrandsAll;
use ReninsApi\Soap\Client as SoapClient;

/**
 * CASCO calculation
 */
trait Calculation
{
    /**
     * @param VehicleBrandsAll $params
     * @return \ReninsApi\Response\Rest\VehicleBrandsAll
     */
    public function calcCasco() {
        /* @var $client SoapClient */
        $client = $this->getSoapClient();
        $client->makeRequest('MakeCalculation');
    }
}