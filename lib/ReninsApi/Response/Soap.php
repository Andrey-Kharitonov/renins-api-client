<?php

namespace ReninsApi\Response;

abstract class Soap
{
    protected $xml;

    public function __construct(string $xml)
    {
        $this->xml = $xml;
        if (strlen($xml)) {
            $this->parseXml();
        }
    }

    abstract protected function parseXml();
}