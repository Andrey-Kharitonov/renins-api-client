<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\Soap\CalculationCasco;
use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Soap\Client as SoapClient;

/**
 * CASCO calculation
 */
trait Calculation
{
    /**
     * @param CalculationCasco $params
     * @return \ReninsApi\Response\Rest\VehicleBrandsAll
     * @throws \Exception
     */
    public function calcCasco(CalculationCasco $params) {
        /* @var $client SoapClient */
        $client = $this->getSoapClient();

        $this->logMessage(__METHOD__, 'Preparing xml');
        try {
            $xml = new \SimpleXMLElement('<Request/>');
            $xml->addAttribute('ClientSystemName', $this->clientSystemName);
            $xml->addAttribute('partnerUid', $this->partnerUid);
            $params->toXml($xml);
            $doc = $xml->asXML();
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        }

        $this->logMessage(__METHOD__, 'Sending request', ['doc' => $doc]);
        try {
            $res = $client->makeRequest('MakeCalculation', ['doc' => $doc]);
            $this->logMessage(__METHOD__, 'Successful', ['request' => $client->getLastRequest(), 'response' => $client->getLastResponse()]);
        } catch(\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['request' => $client->getLastRequest(), 'response' => $client->getLastResponse()]);
            throw $exc;
        }

        print_r($res);
    }
}