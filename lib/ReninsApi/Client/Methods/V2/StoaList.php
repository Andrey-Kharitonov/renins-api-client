<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Rest\GetStoaListResponse;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Stoa/List/*
 */
trait StoaList
{
    /**
     * Method: /Stoa/List
     * @param string $region - марка
     * @param string $year - модель
     * @param string $carBrand - год выпуска
     * @return GetStoaListResponse
     * @throws \Exception
     */
    public function stoaList(string $region, string $year, string $carBrand): GetStoaListResponse {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = [
            'Region' => $region,
            'Year' => $year,
            'CarBrand' => $carBrand,
        ];

        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Stoa/List', $parameters);

            $res = GetStoaListResponse::createFromXml($xml);
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