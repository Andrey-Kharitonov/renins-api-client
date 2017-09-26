<?php

namespace ReninsApi\Soap;

class ClientPrint extends Client
{
    /**
     * Make request. Return first property of object.
     *
     * @param string $method
     * @param array $arguments
     * @return \stdClass|string
     */
    public function makeRequest(string $method, array $arguments = []) {
        $res = parent::makeRequest($method, $arguments);
        if (!is_object($res)) {
            throw new ClientException("Invalid response. Expected object.");
        }

        $firstPropertyValue = reset($res);
        if (!empty($firstPropertyValue->StorageKeyResponseEx)) {
            return $firstPropertyValue->StorageKeyResponseEx;
        }
        if (!empty($firstPropertyValue->DocBytesResponseEx)) {
            return $firstPropertyValue->DocBytesResponseEx;
        }
        return $firstPropertyValue;
    }
}