<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\Soap\Printing\GetAvailablePolicyDocumentTypes;
use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Soap\ClientPrint;

/**
 * Printing
 */
trait Printing
{
    public function getAvailablePolicyDocumentTypes(GetAvailablePolicyDocumentTypes $param) {
        /* @var $client ClientPrint */
        $client = $this->getSoapPrintClient();

        try {
            $this->logMessage(__METHOD__, 'Checking param');
            if ($param->request) {
                $param->request->PartnerName = $this->getClientSystemName();
                $param->request->PartnerUId = $this->getPartnerUid();
            }
            $param->validateThrow();
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        } catch (\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage());
            throw $exc;
        }

        try {
            $args = ['GetAvailablePolicyDocumentTypes' => $param->toArray()];
            $this->logMessage(__METHOD__, 'Making request', $args);
            $res = $client->makeRequest('GetAvailablePolicyDocumentTypes', $args);
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