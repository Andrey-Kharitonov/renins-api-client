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

    public function __construct(string $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    /**
     * Make request
     *
     * @param string $method - http method, default: 'get'
     * @param array|Container|null $parameters
     */
    public function makeRequest(string $method, $parameters = null) {
        $soap = new \SoapClient($this->wsdl, [
            'exceptions' => true,
            'connection_timeout' => 30,
            //'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        $res = $soap->__soapCall($method, []);
        print_r($res);
    }
}