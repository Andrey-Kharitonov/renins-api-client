<?php

namespace ReninsApi\Client;

use ReninsApi\Helpers\LogEvent;
use ReninsApi\Rest\Client as RestClient;
use ReninsApi\Soap\ClientCalc;
use ReninsApi\Soap\ClientImport;
use ReninsApi\Soap\ClientPrint;

abstract class BaseApi
{
    /**
     * Link to calculation service wsdl.
     * Production and test links are different.
     * @var string
     */
    protected $wsdlCalc = '';

    /**
     * Link to import service wsdl
     * Production and test links are different.
     * @var string
     */
    protected $wsdlImport = '';

    /**
     * Link to print service wsdl
     * Production and test links are different.
     * @var string
     */
    protected $wsdlPrint = '';

    /**
     * Link to rest service
     * @var string
     */
    protected $urlRest = '';

    protected $clientSystemName;
    protected $partnerUid;

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
     */
    public function __construct(string $clientSystemName, string $partnerUid = '')
    {
        $this->clientSystemName = $clientSystemName;
        $this->partnerUid = $partnerUid;
    }

    /**
     * @return string
     */
    public function getWsdlCalc(): string
    {
        return $this->wsdlCalc;
    }

    /**
     * @param string $wsdlCalc
     * @return $this
     */
    public function setWsdlCalc(string $wsdlCalc)
    {
        if ($this->wsdlCalc !== $wsdlCalc) {
            $this->wsdlCalc = $wsdlCalc;
            $this->soapCalcClient = null;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getWsdlImport(): string
    {
        return $this->wsdlImport;
    }

    /**
     * @param string $wsdlImport
     * @return $this
     */
    public function setWsdlImport(string $wsdlImport)
    {
        if ($this->wsdlImport !== $wsdlImport) {
            $this->wsdlImport = $wsdlImport;
            $this->soapImportClient = null;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getWsdlPrint(): string
    {
        return $this->wsdlPrint;
    }

    /**
     * @param string $wsdlPrint
     * @return $this
     */
    public function setWsdlPrint(string $wsdlPrint)
    {
        if ($this->wsdlPrint !== $wsdlPrint) {
            $this->wsdlPrint = $wsdlPrint;
            $this->soapPrintClient = null;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlRest(): string
    {
        return $this->urlRest;
    }

    /**
     * @param string $urlRest
     * @return $this
     */
    public function setUrlRest(string $urlRest)
    {
        if ($this->urlRest !== $urlRest) {
            $this->urlRest = $urlRest;
            $this->restClient = null;
        }
        return $this;
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
            $this->restClient = new RestClient($this->urlRest);
        }
        return $this->restClient;
    }

    /**
     * @return ClientCalc
     */
    public function getSoapCalcClient(): ClientCalc
    {
        if (!$this->soapCalcClient) {
            $this->soapCalcClient = new ClientCalc($this->wsdlCalc);
        }
        return $this->soapCalcClient;
    }

    /**
     * @return ClientImport
     */
    public function getSoapImportClient(): ClientImport
    {
        if (!$this->soapImportClient) {
            $this->soapImportClient = new ClientImport($this->wsdlImport);
        }
        return $this->soapImportClient;
    }

    /**
     * @return ClientPrint
     */
    public function getSoapPrintClient(): ClientPrint
    {
        if (!$this->soapPrintClient) {
            $this->soapPrintClient = new ClientPrint($this->wsdlPrint);
        }
        return $this->soapPrintClient;
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