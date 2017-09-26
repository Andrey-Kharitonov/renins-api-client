<?php

namespace ReninsApi\Soap;

class ClientCalc extends Client
{
    /**
     * Make request. Return xml.
     *
     * @param string $method
     * @param array $arguments
     * @return string - xml
     */
    public function makeRequest(string $method, array $arguments = []) {
        $res = parent::makeRequest($method, $arguments);

        if (!is_object($res)
            || empty($res->MakeCalculationResult)
            || !is_object($res->MakeCalculationResult)
            || empty($res->MakeCalculationResult->any)) {
            throw new ClientException("Invalid response. Expected {MakeCalculationResult: any: \"...\"}");
        }

        return $res->MakeCalculationResult->any;
    }
}