<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Soap\Import\InputMessage;
use ReninsApi\Request\Soap\Import\Request;
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
                        'any' => new \SoapVar($xmlStr, XSD_ANYXML)
                    ],
                ]
            ];
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

        /*
        stdClass Object
        (
            [ErrorCode] => 0
            [PolicyId] => 48238630
            [ProlongatedPolicyId] => 0
            [Messages] => stdClass Object
                (
                )

            [AccountNumber] => RNN-260917-616
            [AvailableDocumentTypes] => stdClass Object
                (
                    [PolicyDocumentType] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [Id] => 1
                                    [Name] => Результаты расчета
                                )

                            [1] => stdClass Object
                                (
                                    [Id] => 7
                                    [Name] => Оригинал полиса
                                    [Labels] => [NewCasco, HasStamp, HaveStamp], [NoHasStamp, NewCasco, NoStamp]
                                )

                            [2] => stdClass Object
                                (
                                    [Id] => 8
                                    [Name] => Копия полиса
                                    [Labels] => [HasStamp, NewCasco, HaveStamp], [NewCasco, NoHasStamp, NoStamp]
                                )

                            [3] => stdClass Object
                                (
                                    [Id] => 10
                                    [Name] => Доп.информация
                                )

                        )

                )

            [PrintToken] => PRI_60E62F72B0FC47CEA3C5AA6C7760736F_001096
        )
         */
    }

    public function GetPolicyNumber(Request $param) {
        /* @var $client ClientImport */
        $client = $this->getSoapImportClient();

        try {
            $this->logMessage(__METHOD__, 'Checking param');
            $param->PartnerName = $this->getClientSystemName();
            $param->PartnerUId = $this->getPartnerUid();
            $param->validateThrow();
        } catch (ValidatorMultiException $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage(), ['errors' => $exc->getErrors()]);
            throw $exc;
        } catch (\Exception $exc) {
            $this->logMessage(__METHOD__, $exc->getMessage());
            throw $exc;
        }

        try {
            $args = [
                'GetPolicyNumber' => [
                    'request' => $param->toArray()
                ]
            ];
            $this->logMessage(__METHOD__, 'Making request', $args);
            $res = $client->makeRequest('GetPolicyNumber', $args);
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

        /*
        Success example:
            stdClass Object
            (
                [Success] => 1
                [Number] => 001AT-17/36253-S
                [Messages] => stdClass Object
                    (
                    )

            )
        Failure example:
            stdClass Object
            (
                [Success] =>
                [Messages] => stdClass Object
                    (
                        [Message] => stdClass Object
                            (
                                [_] => Не указан номер котировки
                                [code] => 0
                                [level] => Critical
                            )

                    )

            )
         */
    }
}