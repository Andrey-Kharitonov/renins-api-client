<?php

namespace ReninsApiTest\Client;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;

class ApiVersion2Test extends TestCase
{
    /**
     * @group rest
     * @group soap
     */
    public function testConstruct()
    {
        $client = new ApiVersion2('');
        $this->assertInstanceOf(ApiVersion2::class, $client);
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAll()
    {
        $client = new ApiVersion2('');
        $request = new \ReninsApi\Request\Rest\VehicleBrandsAll();
        $request->vehicleType = 'Легковое ТС';
        $response = $client->vehicleBrandsAll($request);
        $this->assertInstanceOf(\ReninsApi\Response\Rest\VehicleBrandsAll::class, $response);
        $this->assertGreaterThan(10, count($response->getBrands()));
        $this->assertContains('ВАЗ', $response->getBrands());
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAll2()
    {
        $client = new ApiVersion2('');
        $request = new \ReninsApi\Request\Rest\VehicleBrandsAll();
        $request->vehicleType = 'Invalid value';
        $response = $client->vehicleBrandsAll($request);
        $this->assertInstanceOf(\ReninsApi\Response\Rest\VehicleBrandsAll::class, $response);
        $this->assertEquals(0, count($response->getBrands()));
    }

    /**
     * @group soap
     */
    public function testCalcCasco()
    {
        $client = new ApiVersion2('Link_Auto', 'CB7D9681-7E19-4CCA-8A3A-D1437CDEBD9F');
        /*
        $request = new \ReninsApi\Request\Rest\VehicleBrandsAll();
        $request->vehicleType = 'Invalid value';
        */
        $client->calcCasco();
    }


}
