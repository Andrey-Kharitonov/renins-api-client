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
        $this->assertEquals(0, $response->Brand->count());
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAllWithModels()
    {
        $client = $this->createApi2();

        $response = $client->vehicleBrandsAllWithModels('Легковое ТС');
        $this->assertInstanceOf(ContainerCollection::class, $response->Brand);
        $this->assertGreaterThan(10, $response->Brand->count());
    }

    /**
     * @group rest
     */
    public function testVehicleModelsBrandName()
    {
        $client = $this->createApi2();

        $response = $client->vehicleModelsBrandName('ВАЗ', true);

        //print_r($response->toArray());

        $this->assertInstanceOf(ContainerCollection::class, $response->Model);
        $this->assertGreaterThan(10, $response->Model->count());
    }

    /**
     * @group rest
     */
    public function testVehicleAntitheftDevicesXml()
    {
        $client = $this->createApi2();

        $response = $client->vehicleAntitheftDevicesXml(1);

        $this->assertInstanceOf(ContainerCollection::class, $response->AlarmSystem);
        $this->assertGreaterThan(10, $response->AlarmSystem->count());
    }

    /**
     * @group rest
     */
    public function testVehicleAntitheftDevicesXmlAll()
    {
        $client = $this->createApi2();

        $response = $client->vehicleAntitheftDevicesXmlAll();

        $this->assertInstanceOf(ContainerCollection::class, $response->AlarmSystem);
        $this->assertGreaterThan(10, $response->AlarmSystem->count());
    }

    /**
     * @group rest
     */
    public function testCreditBanksAll()
    {
        $client = $this->createApi2();

        $response = $client->creditBanksAll();

        $this->assertInstanceOf(ContainerCollection::class, $response->Bank);
        $this->assertGreaterThan(10, $response->Bank->count());
    }

    /**
     * @group rest
     */
    public function testCreditLeasingAll()
    {
        $client = $this->createApi2();

        $response = $client->creditLeasingAll();

        $this->assertInstanceOf(ContainerCollection::class, $response->Leasing);
        $this->assertGreaterThan(10, $response->Leasing->count());
    }

    /**
     * @group rest
     */
    public function testPriceCalculated()
    {
        $client = $this->createApi2();

        $response = $client->priceCalculated('Honda', 'Accord', '2015', '190');

        //print_r($response->toArray());

        $this->assertEquals('true', $response->Success);
        $this->assertInstanceOf(\ReninsApi\Response\Rest\DiapasonPrice::class, $response->Price);
        $this->assertGreaterThan(0, $response->Price->MinValue);
        $this->assertGreaterThan(0, $response->Price->MaxValue);
    }

    /**
     * @group rest
     */
    public function testPriceCalculatedFail()
    {
        $client = $this->createApi2();

        $response = $client->priceCalculated('', '', '', '');

        $this->assertEquals('false', $response->Success);
        $this->assertGreaterThan(0, strlen($response->Error));
    }

    /**
     * @group rest
     * @group current
     */
    public function testStoaListFail()
    {
        $client = $this->createApi2();

        $response = $client->stoaList('Error here', '2017', 'Error here');

        $this->assertEquals('false', $response->Success);
        $this->assertGreaterThan(0, strlen($response->ErrorMessage));
    }
}
