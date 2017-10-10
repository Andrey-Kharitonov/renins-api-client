<?php

namespace ReninsApi\Soap;

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
     * Last header tag
     * For example:
     * [
     *   "XsdValidation" => stdClass Object
     *   (
     *     [In] => "The required attribute 'uid' is missing.
     *       The 'MultiDrive' attribute is not declared.
     *       The required attribute 'Multidrive' is missing."
     *   )
     *   "MessageId" => "77e95d78-a3d9-47fc-b21d-8f2aca29d274"
     *   "ExecutionTime" => "00:00:14.0338612"
     * ]
     * @var array
     */
    protected $lastHeader = [];

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
     * @return array
     */
    public function getLastHeader(): array
    {
        return $this->lastHeader;
    }

    /**
     * Make request
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function makeRequest(string $method, array $arguments = []) {
        $soap = new \SoapClient($this->wsdl, [
            'exceptions' => true,
            'connection_timeout' => 30,
            //'soap_version' => SOAP_1_2,
            //'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => true,
        ]);

        $this->lastHeader = [];
        try {
            $res = $soap->__soapCall($method, $arguments, null, null, $this->lastHeader);
        } finally {
            $this->lastRequest = $soap->__getLastRequestHeaders() . $soap->__getLastRequest();
            $this->lastResponse = $soap->__getLastResponseHeaders() . $soap->__getLastResponse();
        }

        return $res;
    }
}