<?php

namespace ReninsApiTest\Client;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Request\ContainerCollection;

class ApiVersion2RestTest extends TestCase
{
    public function onLog(LogEvent $event) {
        global $loggerCalc;
        $loggerCalc->info("{$event->method}: {$event->message}", $event->data);
    }

    /**
     * @group rest
     */
    public function testConstruct()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $this->assertInstanceOf(ApiVersion2::class, $client);
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAll()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

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
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $response = $client->vehicleBrandsAll('Invalid value');
        $this->assertInstanceOf(\ReninsApi\Response\Rest\ArrayOfBrand::class, $response);
        $this->assertEquals(0, $response->Brand->count());
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAllWithModels()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $response = $client->vehicleBrandsAllWithModels('Легковое ТС');
        $this->assertInstanceOf(\ReninsApi\Response\Rest\ArrayOfBrand::class, $response);
        $this->assertInstanceOf(ContainerCollection::class, $response->Brand);
        $this->assertGreaterThan(10, $response->Brand->count());
    }
}
