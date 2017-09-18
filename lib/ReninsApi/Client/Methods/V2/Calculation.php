<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Soap\CalculationCasco;
use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Soap\Client as SoapClient;
use ReninsApi\Soap\ClientResponseException as SoapClientResponseException;

/**
 * Calculation
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
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><MakeCalculation xmlns="http://renins.com/"></MakeCalculation>');
            $doc = $xml->addChild('doc');
            $request = $doc->addChild('Request', null, '');
            $request->addAttribute('ClientSystemName', $this->clientSystemName);
            $request->addAttribute('partnerUid', $this->partnerUid);
            $params->toXml($request);
            $xmlStr = Utils::sxmlToStr($xml);
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        } catch (\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage());
            throw $exc;
        }

        $this->logMessage(__METHOD__, 'Making request', [$xmlStr]);
        try {
            $args = [new \SoapVar($xmlStr, XSD_ANYXML)];
            $res = $client->makeRequest('MakeCalculation', $args);
            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
                'header' => $client->getLastHeader(),
            ]);
        } catch(SoapClientResponseException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
                'header' => $client->getLastHeader(),
                'errors' => $exc->getErrors(),
            ]);
            throw $exc;
        } catch(\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
            ]);
            throw $exc;
        }

        print_r($res);
    }
}