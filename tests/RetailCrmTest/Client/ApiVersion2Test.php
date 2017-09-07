<?php

namespace RetailCrmTest\Client;

use PHPUnit\Framework\TestCase;
use RetailCrm\Client\ApiVersion2;

class ApiVersion2Test extends TestCase
{
    public function testConstruct()
    {
        $client = new ApiVersion2('');
        $this->assertInstanceOf(ApiVersion2::class, $client);
    }

    public function testVehicleBrandsAll()
    {
        $client = new ApiVersion2('');
        $request = new \RetailCrm\Request\Rest\VehicleBrandsAll();
        $request->vehicleType = 'Легковое ТС';
        $response = $client->vehicleBrandsAll($request);
        $this->assertInstanceOf(\RetailCrm\Response\Rest\VehicleBrandsAll::class, $response);
        $this->assertGreaterThan(10, count($response->getBrands()));
        $this->assertContains('ВАЗ', $response->getBrands());
    }

    public function testVehicleBrandsAll2()
    {
        $client = new ApiVersion2('');
        $request = new \RetailCrm\Request\Rest\VehicleBrandsAll();
        $request->vehicleType = 'Invalid value';
        $response = $client->vehicleBrandsAll($request);
        $this->assertInstanceOf(\RetailCrm\Response\Rest\VehicleBrandsAll::class, $response);
        $this->assertEquals(0, count($response->getBrands()));
    }

}
