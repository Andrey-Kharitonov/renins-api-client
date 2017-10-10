<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Soap\Import\InputMessage;
use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Soap\ClientImport;

/**
 * Import
 */
trait Import
{
    public function ImportPolicy(InputMessage $param) {
        /* @var $client ClientImport */
        $client = $this->getSoapImportClient();

        try {
            $this->logMessage(__METHOD__, 'Preparing xml');
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><InputMessage xmlns="http://schemas.renins.com/xsd/sfa/ProcessManager.xsd"></InputMessage>');
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
                'ImportPolicy' => [
                    'partnerName' => $this->getClientSystemName(),
                    'partnerUId' => $this->getPartnerUid(),
                    'doc' => [
                        'any' => new \SoapVar($xmlStr, XSD_ANYXML) //А вот догадайся сам
                    ],
                ]
            ];
            $args['partnerName'] = $this->getClientSystemName();
            $args['partnerUId'] = $this->getPartnerUid();
            $args['doc'] = new \SoapVar($xmlStr, XSD_ANYXML);

            $this->logMessage(__METHOD__, 'Making request', $args);
            $res = $client->makeRequest('ImportPolicy', $args);
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