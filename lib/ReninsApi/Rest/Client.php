<?php

namespace ReninsApi\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Helpers\Utils;

/**
 * Rest client (GET, POST)
 */
class Client
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    protected $url = false;

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

    public function __construct(string $url)
    {
        $this->url = $url;
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
     * Make GET request
     * @see Client::makeRequest()
     *
     * @param string $path
     * @param array|Container|null $parameters
     *
     * @return string
     */
    public function get(string $path, $parameters = null) {
        return $this->makeRequest($path, self::METHOD_GET, $parameters);
    }

    /**
     * Make POST request
     * @see Client::makeRequest()
     *
     * @param string $path
     * @param array|Container|null $parameters
     *
     * @return string
     */
    public function post(string $path, $parameters = null) {
        return $this->makeRequest($path, self::METHOD_POST, $parameters);
    }

    /**
     * Make HTTP request
     *
     * @param string $path - request uri
     * @param string $method - http method, default: 'get'
     * @param array|Container|null $parameters - Parameters
     *
     * @throws \InvalidArgumentException
     * @throws CurlException
     *
     * @return string
     */
    public function makeRequest(string $path, string $method = self::METHOD_GET, $parameters = null) {
        $allowedMethods = [self::METHOD_GET, self::METHOD_POST];

        if (!in_array($method, $allowedMethods, false)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Method "%s" is not valid. Allowed methods are %s',
                    $method,
                    implode(', ', $allowedMethods)
                )
            );
        }

        $url = Utils::pathCombine($this->url, $path);

        if ($parameters) {
            if ($parameters instanceof Container) {
                $parameters = $parameters->toArray();
            } elseif (!is_array($parameters)) {
                $parameters = (array) $parameters;
            }
        }

        if (self::METHOD_GET === $method && $parameters) {
            $url .= '?' . http_build_query($parameters, '', '&');
        }

        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_URL, $url);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandler, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlHandler, CURLOPT_FAILONERROR, false);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curlHandler, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandler, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curlHandler, CURLOPT_HEADER, true);
        curl_setopt($curlHandler, CURLINFO_HEADER_OUT, true);

        if (self::METHOD_POST === $method) {
            curl_setopt($curlHandler, CURLOPT_POST, true);
            if ($parameters) {
                curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $parameters);
            }
        }

        $response = curl_exec($curlHandler);
        $this->lastResponse = $response;
        $errno = curl_errno($curlHandler);
        $error = curl_error($curlHandler);
        $this->lastRequest = curl_getinfo($curlHandler, CURLINFO_HEADER_OUT);
        curl_close($curlHandler);

        if ($errno) {
            throw new CurlException($error, $errno);
        }

        $pos = strpos($response, "\r\n\r\n");
        if ($pos === false) {
            throw new CurlException("Response body is empty");
        }
        $responseBody = substr($response, $pos + 4);
        if (strlen($responseBody) <= 0) {
            throw new CurlException("Response body is empty");
        }

        return $responseBody;
    }
}