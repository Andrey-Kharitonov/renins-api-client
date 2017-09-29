<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Rest\ArrayOfLeasing;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Credit/Leasing/*
 */
trait CreditLeasing
{
    /**
     * Method: /Credit/Leasing/All
     * @return ArrayOfLeasing
     * @throws \Exception
     */
    public function creditLeasingAll(): ArrayOfLeasing {
        /* @var $client RestClient */
        $client = $this->getRestClient();

        $this->logMessage(__METHOD__, 'Making request');
        try {
            $xml = $client->get('Credit/Leasing/All');

            $res = ArrayOfLeasing::createFromXml($xml);
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