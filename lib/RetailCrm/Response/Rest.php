<?php

namespace RetailCrm\Response;

abstract class Rest
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