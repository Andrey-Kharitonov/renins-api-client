<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Rest\AntitheftDevicesDictionary;
use ReninsApi\Rest\Client as RestClient;

/**
 * Methods /Vehicle/AntitheftDevices/*
 */
trait VehicleAntitheftDevices
{
    /**
     * Method: /Vehicle/AntitheftDevices/Xml
     * @param integer $type - тип системы:
     *   0 – ПСС (Поисковая система);
     *   1 – ПУУ (Противоугонная система);
     *   2 – Закладка
     * @param string $uid - уникальный ID системы
     * @return AntitheftDevicesDictionary
     * @throws \Exception
     */
    public function vehicleAntitheftDevicesXml(int $type = null, string $uid = null): AntitheftDevicesDictionary {
        /* @var $client RestClient */
        $client = $this->getRestClient();
        $parameters = ['Uid' => $uid, 'Type' => $type];

        $this->logMessage(__METHOD__, 'Making request', $parameters);
        try {
            $xml = $client->get('Vehicle/AntitheftDevices/Xml', $parameters);

            $res = AntitheftDevicesDictionary::createFromXml($xml);
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

    /**
     * Method: /Vehicle/AntitheftDevices/Xml/All
     * @return AntitheftDevicesDictionary
     * @throws \Exception
     */
    public function vehicleAntitheftDevicesXmlAll(): AntitheftDevicesDictionary {
        /* @var $client RestClient */
        $client = $this->getRestClient();

        $this->logMessage(__METHOD__, 'Making request');
        try {
            $xml = $client->get('Vehicle/AntitheftDevices/Xml/All');

            $res = AntitheftDevicesDictionary::createFromXml($xml);
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