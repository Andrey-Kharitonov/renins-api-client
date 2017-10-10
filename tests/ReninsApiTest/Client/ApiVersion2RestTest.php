<?php

namespace ReninsApiTest\Client;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;

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
        $this->assertInstanceOf(\ReninsApi\Response\Rest\VehicleBrandsAll::class, $response);
        $this->assertGreaterThan(10, count($response->getBrands()));

        $filtered = array_filter($response->getBrands(), function($item) {
            return $item['Name'] == 'ВАЗ';
        });
        $this->assertEquals(1, count($filtered));
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAll2()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $response = $client->vehicleBrandsAll('Invalid value');
        $this->assertInstanceOf(\ReninsApi\Response\Rest\VehicleBrandsAll::class, $response);
        $this->assertEquals(0, count($response->getBrands()));
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAllWithModels()
    {
        $client = new ApiVersion2(self::CLIENT_SYSTEM_NAME);
        $client->onLog = [$this, 'onLog'];

        $response = $client->vehicleBrandsAllWithModels('Легковое ТС');
        $this->assertInstanceOf(\ReninsApi\Response\Rest\VehicleBrandsAll::class, $response);
        $this->assertGreaterThan(10, count($response->getBrands()));

        $filtered = array_filter($response->getBrands(), function($item) {
            return $item['Name'] == 'ВАЗ';
        });
        $this->assertEquals(1, count($filtered));

        $filtered = array_values($filtered);
        $this->assertArrayHasKey('Models', $filtered[0]);
        $this->assertGreaterThan(10, count($filtered[0]['Models']));
    }
}
