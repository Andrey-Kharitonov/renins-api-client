<?php

namespace ReninsApi\Client;

use ReninsApi\Helpers\LogEvent;
use ReninsApi\Rest\Client as RestClient;
use ReninsApi\Soap\ClientCalc;
use ReninsApi\Soap\ClientImport;
use ReninsApi\Soap\ClientPrint;

abstract class BaseApi
{
    protected static $wsdlCalc = '';
    protected static $wsdlCalcTest = '';

    protected static $wsdlImport = '';
    protected static $wsdlImportTest = '';

    protected static $wsdlPrint = '';
    protected static $wsdlPrintTest = '';

    protected static $urlRest = '';
    protected static $urlRestTest = '';

    protected $clientSystemName;
    protected $partnerUid;
    protected $test;

    /**
     * Rest client instance
     * @var RestClient
     */
    protected $restClient;

    /**
     * Soap client instance for calculation
     * @var ClientCalc
     */
    protected $soapCalcClient;

    /**
     * Soap client instance for calculation
     * @var ClientImport
     */
    protected $soapImportClient;

    /**
     * Soap client instance for printing
     * @var ClientPrint
     */
    protected $soapPrintClient;

    /**
     * Log callback
     * @var mixed
     */
    public $onLog;

    /**
     * Init version based client
     *
     * @param string $clientSystemName
     * @param string $partnerUid
     * @param bool $test
     */
    public function __construct(string $clientSystemName, string $partnerUid = '', $test = true)
    {
        $this->clientSystemName = $clientSystemName;
        $this->partnerUid = $partnerUid;
        $this->test = (bool) $test;
    }

    /**
     * @return string
     */
    public function getClientSystemName(): string
    {
        return $this->clientSystemName;
    }

    /**
     * @return string
     */
    public function getPartnerUid(): string
    {
        return $this->partnerUid;
    }

    /**
     * @return RestClient
     */
    public function getRestClient(): RestClient
    {
        if (!$this->restClient) {
            $this->restClient = new RestClient(($this->test) ? static::$urlRestTest : static::$urlRest);
        }
        return $this->restClient;
    }

    /**
     * @return ClientCalc
     */
    public function getSoapCalcClient(): ClientCalc
    {
        if (!$this->soapCalcClient) {
            $this->soapCalcClient = new ClientCalc(($this->test) ? static::$wsdlCalcTest : static::$wsdlCalc);
        }
        return $this->soapCalcClient;
    }

    /**
     * @return ClientImport
     */
    public function getSoapImportClient(): ClientImport
    {
        if (!$this->soapImportClient) {
            $this->soapImportClient = new ClientImport(($this->test) ? static::$wsdlImportTest : static::$wsdlImport);
        }
        return $this->soapImportClient;
    }

    /**
     * @return ClientPrint
     */
    public function getSoapPrintClient(): ClientPrint
    {
        if (!$this->soapPrintClient) {
            $this->soapPrintClient = new ClientPrint(($this->test) ? static::$wsdlPrintTest : static::$wsdlPrint);
        }
        return $this->soapPrintClient;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->test;
    }

    /**
     * Help method. Send a xml to describer
     * @param string $method
     * @param string $msg
     * @param array|object|null $data
     * @return $this
     */
    protected function logMessage(string $method, string $msg, $data = []) {
        if (is_callable($this->onLog)) {
            $event = new LogEvent();
            $event->method = $method;
            $event->message = $msg;
            $event->data = (array) $data;
            call_user_func($this->onLog, $event);
        }
        return $this;
    }

}