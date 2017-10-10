<?php

namespace ReninsApi\Soap;

use ReninsApi\Request\Container;

/**
 * Soap client
 */
class Client
{
    /**
     * Url to WSDL
     * @var string
     */
    protected $wsdl;

    /**
     * Last request with headers
     * @var string
     */
    protected $lastRequest;

    /**
     * Last response with headers
     * @var string
     */
    protected $lastResponse;

    public function __construct(string $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    /**
     * @return string
     */
    public function getLastRequest(): string
    {
        return $this->lastRequest;
    }

    /**
     * @return string
     */
    public function getLastResponse(): string
    {
        return $this->lastResponse;
    }

    /**
     * Make request
     *
     * @param string $method - http method, default: 'get'
     * @param array|Container|null $parameters
     * @return mixed
     */
    public function makeRequest(string $method, $parameters = null) {
        if ($parameters) {
            if ($parameters instanceof Container) {
                $parameters = $parameters->toArray();
            } elseif (!is_array($parameters)) {
                $parameters = (array) $parameters;
            }
        }

        $soap = new \SoapClient($this->wsdl, [
            'exceptions' => true,
            'connection_timeout' => 30,
            //'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => true,
        ]);

        try {
            $res = $soap->__soapCall($method, $parameters);
        } finally {
            $this->lastRequest = $soap->__getLastRequestHeaders() . $soap->__getLastRequest();
            $this->lastResponse = $soap->__getLastResponseHeaders() . $soap->__getLastResponse();
        }

        return $res;
    }
}