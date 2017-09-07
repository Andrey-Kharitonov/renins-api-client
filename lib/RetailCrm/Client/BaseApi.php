<?php

namespace RetailCrm\Client;

use RetailCrm\Rest\Client as RestClient;

abstract class BaseApi
{
    const URL_SOAP_TEST = '';
    const URL_SOAP = '';

    const URL_REST = '';
    const URL_REST_TEST = self::URL_REST;

    protected $clientSystemName;
    protected $partnerUid;
    protected $test;

    protected $restClient;

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
            $this->restClient = new RestClient(($this->test) ? self::URL_REST_TEST : self::URL_REST);
        }
        return $this->restClient;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->test;
    }
}