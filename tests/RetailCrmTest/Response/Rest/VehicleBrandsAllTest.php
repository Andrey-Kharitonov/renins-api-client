<?php

namespace RetailCrmTest\Response\Rest;

use PHPUnit\Framework\TestCase;
use RetailCrm\Helpers\Utils;
use RetailCrm\Response\Rest\VehicleBrandsAll;

class VehicleBrandsAllTest extends TestCase
{
    public function testConstruct()
    {
        $xml = @file_get_contents(Utils::pathCombine(DATA_DIR, 'VehicleBrandsAll_response.xml'));
        $resp = new VehicleBrandsAll($xml);
        $this->assertInstanceOf(VehicleBrandsAll::class, $resp);
        $this->assertEquals(3, count($resp->getBrands()));
        $this->assertContains('Яровит Моторс', $resp->getBrands());
    }

    public function testConstructInvalidXml()
    {
        $resp = new VehicleBrandsAll('qwe9g2v2');
        $this->assertInstanceOf(VehicleBrandsAll::class, $resp);
        $this->assertEquals(0, count($resp->getBrands()));
    }
}