<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Request\ContainerCollection;
use ReninsApiTest\Client\Log;

class RestTest extends TestCase
{
    use Log;

    /**
     * @group rest
     */
    public function testConstruct()
    {
        $client = $this->createApi2();
        $this->assertInstanceOf(ApiVersion2::class, $client);
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAll()
    {
        $client = $this->createApi2();

        $response = $client->vehicleBrandsAll('Легковое ТС');
        $this->assertInstanceOf(\ReninsApi\Response\Rest\ArrayOfBrand::class, $response);
        $this->assertInstanceOf(ContainerCollection::class, $response->Brand);
        $this->assertGreaterThan(10, $response->Brand->count());
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAll2()
    {
        $client = $this->createApi2();

        $response = $client->vehicleBrandsAll('Invalid value');
        $this->assertInstanceOf(\ReninsApi\Response\Rest\ArrayOfBrand::class, $response);
        $this->assertEquals(0, $response->Brand->count());
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAllWithModels()
    {
        $client = $this->createApi2();

        $response = $client->vehicleBrandsAllWithModels('Легковое ТС');
        $this->assertInstanceOf(\ReninsApi\Response\Rest\ArrayOfBrand::class, $response);
        $this->assertInstanceOf(ContainerCollection::class, $response->Brand);
        $this->assertGreaterThan(10, $response->Brand->count());
    }
}
