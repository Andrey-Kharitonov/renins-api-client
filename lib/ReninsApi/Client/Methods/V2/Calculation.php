<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Soap\Calculation\Request;
use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Soap\Calculation\MakeCalculationResult;
use ReninsApi\Soap\ClientCalc;

/**
 * Calculation
 */
trait Calculation
{
    /**
     * @param Request $param
     * @return MakeCalculationResult
     * @throws \Exception
     */
    public function calcCasco(Request $param) {
        /* @var $client ClientCalc */
        $client = $this->getSoapCalcClient();

        try {
            $this->logMessage(__METHOD__, 'Preparing xml');
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Request></Request>');
            $xml->addAttribute('ClientSystemName', $this->clientSystemName);
            $xml->addAttribute('partnerUid', $this->partnerUid);
            $param->validateThrow();
            $param->toXml($xml);
            $xmlStr = Utils::sxmlToStr($xml);
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        } catch (\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage());
            throw $exc;
        }

        try {
            $args = [
                'MakeCalculation' => [
                    'doc' => [
                        'any' => new \SoapVar($xmlStr, XSD_ANYXML)
                    ],
                ]
            ];
            $this->logMessage(__METHOD__, 'Making request', $args);
            $res = $client->makeRequest('MakeCalculation', $args);

            $res = MakeCalculationResult::createFromXml($res);
            $res->validateThrow();

            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
                'header' => $client->getLastHeader(),
            ]);
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