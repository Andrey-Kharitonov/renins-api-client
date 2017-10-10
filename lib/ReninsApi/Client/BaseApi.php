<?php

namespace ReninsApi\Client;

use ReninsApi\Rest\Client as RestClient;
use ReninsApi\Soap\Client as SoapClient;

abstract class BaseApi
{
    protected static $wsdl = '';
    protected static $wsdlTest = '';

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
     * Soap client instance
     * @var SoapClient
     */
    protected $soapClient;

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
     * @return SoapClient
     */
    public function getSoapClient(): SoapClient
    {
        if (!$this->soapClient) {
            $this->soapClient = new SoapClient(($this->test) ? static::$wsdlTest : static::$wsdl);
        }
        return $this->soapClient;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->test;
    }
}