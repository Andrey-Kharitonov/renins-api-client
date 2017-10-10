<?php

namespace ReninsApi\Client\Methods\V2;

use ReninsApi\Request\Soap\Printing\Request;
use ReninsApi\Request\ValidatorMultiException;
use ReninsApi\Response\Soap\Printing\GetAvailablePolicyDocumentTypesResult;
use ReninsApi\Response\Soap\Printing\PrintDocumentsResult;
use ReninsApi\Response\Soap\Printing\PrintDocumentsToBinaryResult;
use ReninsApi\Soap\ClientPrint;

/**
 * Printing
 */
trait Printing
{
    /**
     * @param Request $param
     * @return GetAvailablePolicyDocumentTypesResult
     * @throws \Exception
     */
    public function getAvailablePolicyDocumentTypes(Request $param): GetAvailablePolicyDocumentTypesResult {
        /* @var $client ClientPrint */
        $client = $this->getSoapPrintClient();

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
                'GetAvailablePolicyDocumentTypes' => [
                    'request' => $param->toArray()
                ]
            ];
            $this->logMessage(__METHOD__, 'Making request', $args);
            $obj = $client->makeRequest('GetAvailablePolicyDocumentTypes', $args);

            $res = GetAvailablePolicyDocumentTypesResult::createFromObject($obj);
            $res->validateThrow();

            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
                'header' => $client->getLastHeader(),
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


        /*
        Success example:
          object(stdClass)#30 (3) {
            ["Success"]=>
            bool(true)
            ["PolicyDocumentTypes"]=>
            object(stdClass)#34 (1) {
              ["PolicyDocumentType"]=>
              array(2) {
                [0]=>
                object(stdClass)#35 (2) {
                  ["Id"]=>
                  int(1)
                  ["Name"]=>
                  string(35) "Результаты расчета"
                }
                [1]=>
                object(stdClass)#36 (3) {
                  ["Id"]=>
                  int(7)
                  ["Name"]=>
                  string(29) "Оригинал полиса"
                  ["Labels"]=>
                  string(64) "[NewCasco, HasStamp, HaveStamp], [NoHasStamp, NewCasco, NoStamp]"
                }
              }
            }
            ["Errors"]=>
            object(stdClass)#37 (0) {
            }
          }

        Error example:
        object(stdClass)#30 (2) {
          ["Success"]=>
          bool(false)
          ["Errors"]=>
          object(stdClass)#34 (1) {
            ["Error"]=>
            object(stdClass)#35 (3) {
              ["Code"]=>
              int(0)
              ["Level"]=>
              string(8) "Critical"
              ["Message"]=>
              string(148) "Ошибка аутентификации партнера, для указанного номера котировки токен не найден"
            }
          }
        }
        */
    }

    /**
     * @param Request $param
     * @return PrintDocumentsResult
     * @throws \Exception
     */
    public function printDocuments(Request $param): PrintDocumentsResult {
        /* @var $client ClientPrint */
        $client = $this->getSoapPrintClient();

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
                'PrintDocuments' => [
                    'request' => $param->toArray()
                ]
            ];
            $this->logMessage(__METHOD__, 'Making request', $args);
            $obj = $client->makeRequest('PrintDocuments', $args);

            $res = PrintDocumentsResult::createFromObject($obj);
            $res->validateThrow();

            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
                'header' => $client->getLastHeader(),
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

        /*
        Success example:
          object(stdClass)#282 (5) {
            ["ExtensionData"]=>
            object(stdClass)#281 (0) {
            }
            ["Errors"]=>
            object(stdClass)#280 (0) {
            }
            ["Result"]=>
            object(stdClass)#279 (4) {
              ["ExtensionData"]=>
              object(stdClass)#278 (0) {
              }
              ["PdfPageCount"]=>
              int(1)
              ["PrintResultType"]=>
              string(3) "Pdf"
              ["TemporaryKey"]=>
              string(54) "DOC_0102_974D6259ED4A46FE88D1797C431363E7_192168001095"
            }
            ["Success"]=>
            bool(true)
            ["Messages"]=>
            object(stdClass)#277 (0) {
            }
          }

        Error example:
          object(stdClass)#282 (3) {
            ["Errors"]=>
            object(stdClass)#281 (0) {
            }
            ["Success"]=>
            bool(false)
            ["Messages"]=>
            object(stdClass)#280 (1) {
              ["Message"]=>
              object(stdClass)#279 (3) {
                ["_"]=>
                string(269) "Не найдено ни одного доступного документа типа: 1 (Метки: [NoHasStamp]). Доступны следующие типы документов: 1, 7 (Метки: [NewCasco, HasStamp, HaveStamp], [NoHasStamp, NewCasco, NoStamp])."
                ["code"]=>
                int(0)
                ["level"]=>
                string(4) "Info"
              }
            }
          }
        */
    }

    /**
     * @param Request $param
     * @return PrintDocumentsToBinaryResult
     * @throws \Exception
     */
    public function printDocumentsToBinary(Request $param): PrintDocumentsToBinaryResult {
        /* @var $client ClientPrint */
        $client = $this->getSoapPrintClient();

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
                'PrintDocumentsToBinary' => [
                    'request' => $param->toArray()
                ]
            ];
            $this->logMessage(__METHOD__, 'Making request', $args);
            $obj = $client->makeRequest('PrintDocumentsToBinary', $args);

            $res = PrintDocumentsToBinaryResult::createFromObject($obj);
            $res->validateThrow();

            $this->logMessage(__METHOD__, 'Successful', [
                'request' => $client->getLastRequest(),
                'response' => $client->getLastResponse(),
                'header' => $client->getLastHeader(),
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

        /*
        Success example:
            object(stdClass)#282 (4) {
              ["Errors"]=>
              object(stdClass)#281 (0) {
              }
              ["Result"]=>
              object(stdClass)#280 (3) {
                ["DocBytes"]=>
                string(65900) "..."
                ["PdfPageCount"]=>
                int(0)
                ["PrintResultType"]=>
                string(9) "Undefined"
              }
              ["Success"]=>
              bool(true)
              ["Messages"]=>
              object(stdClass)#279 (0) {
              }
            }

        */
    }

    /**
     * @param string $method
     * @param string $storageKey
     * @return string
     * @throws \Exception
     */
    public function makeRequestWithStorageKey(string $method, string $storageKey): string {
        /* @var $client ClientPrint */
        $client = $this->getSoapPrintClient();

        try {
            $args = [
                $method => [
                    'storageKey' => $storageKey
                ]
            ];
            $this->logMessage(__METHOD__, 'Making request', $args);
            $res = $client->makeRequest($method, $args);
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

    /**
     * Will return
     * Doesn't return error
     *
     * @param string $storageKey
     * @return string
     * @throws \Exception
     */
    public function getDocumentInUrl(string $storageKey): string {
        return $this->makeRequestWithStorageKey('GetDocumentInUrl', $storageKey);
    }

    /**
     * Will return pdf content,
     * empty string - if storage key not found
     *
     * @param string $storageKey
     * @return string
     * @throws \Exception
     */
    public function getDocumentToBytes(string $storageKey): string {
        return $this->makeRequestWithStorageKey('GetDocumentToBytes', $storageKey);
    }

}