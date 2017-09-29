<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\Validator;
use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Rest\CalculatedPriceResponse;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Price/Calculated/*
 */
trait PriceCalculated
{
    /**
     * Method: /Price/Calculated
     * @param string $brand - марка
     * @param string $model - модель
     * @param string $year - год выпуска
     * @param string $power - мощность двигателя
     *   Если указана мощность, которой нет в списке, то вилка стоимости
     *   рассчитывается в границах от минимально возможной мощности до максимально возможной
     * @param string $body - тип кузова
     * @param string $gear - тип КПП
     * @param string $engine - тип двигателя
     * @return CalculatedPriceResponse
     * @throws \Exception
     * @see Validator::checkVehicleBodyType()
     * @see Validator::checkTransmissionType() not 'import'
     * @see Validator::checkEngineType()
     */
    public function priceCalculated(string $brand, string $model, string $year, string $power, string $body = '', string $gear = '', string $engine = ''): CalculatedPriceResponse {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = [
            'Brand' => $brand,
            'Model' => $model,
            'Year' => $year,
            'Power' => $power,
            'Body' => $body,
            'Gear' => $gear,
            'Engine' => $engine,
        ];

        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Price/Calculated', $parameters);

            $res = CalculatedPriceResponse::createFromXml($xml);
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