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
     * @param string $method - http method, default: 'get'
     * @param array $arguments
     * @return \SimpleXMLElement
     */
    public function makeRequest(string $method, array $arguments = []): \SimpleXMLElement {
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

        print_r($res);

        if (!is_object($res)
            || empty($res->MakeCalculationResult)
            || !is_object($res->MakeCalculationResult)
            || empty($res->MakeCalculationResult->any)) {
            throw new ClientException("Unexpected type of answer. Expected {MakeCalculationResult: any: \"...\"}");
        }

        $xml = $res->MakeCalculationResult->any;
        $xmlObj = new \SimpleXMLElement($xml);

        $calcResultsNode = $xmlObj->xpath('/root/CalcResults');
        if (!$calcResultsNode) {
            throw new ClientException("Path /root/CalcResults not found");
        }

        if ($calcResultsNode[0]['Success'] == 'false') {
            $errors = [];
            foreach($calcResultsNode[0]->Messages->Message as $message) {
                $errors[] = ((trim($message['level']) != '') ? strtoupper(trim($message['level'])) . ' ' : '') . ((trim($message['code'])) ? trim($message['code']) . '. ' : '') . $message;
            }

            $exc = new ClientResponseException("Request error");
            $exc->setErrors($errors);
            throw $exc;
        }

        return $xmlObj;
    }
}