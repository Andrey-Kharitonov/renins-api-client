<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Rest\ArrayOfBank;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Credit/Banks/*
 */
trait CreditBanks
{
    /**
     * Method: /Credit/Banks/All
     * @return ArrayOfBank
     * @throws \Exception
     */
    public function creditBanksAll(): ArrayOfBank {
        /* @var $client RestClient */
        $client = $this->getRestClient();

        $this->logMessage(__METHOD__, 'Making request');
        try {
            $xml = $client->get('Credit/Banks/All');

            $res = ArrayOfBank::createFromXml($xml);
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