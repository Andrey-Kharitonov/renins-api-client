<?php

namespace RetailCrm\Response;

abstract class Rest
{
    protected $xml;

    public function __construct(string $xml)
    {
        $this->xml = $xml;
        $this->parseXml();
    }

    abstract protected function parseXml();
}