<?php

namespace ReninsApiTest\Client;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Helpers\Utils;
use ReninsApi\Request\ContainerCollection;

class ApiVersion2Test extends TestCase
{
    CONST CLIENT_SYSTEM_NAME = '';
    CONST PARTNER_UID = '';

    public function onLog(LogEvent $event) {
        global $loggerCalc;
        $loggerCalc->info("{$event->method}: {$event->message}", $event->data);
    }

    /**
     * @group rest
     * @group soap
     */
    public function testConstruct()
    {
        $client = new ApiVersion2(self::CLIENT_SYSTEM_NAME);
        $this->assertInstanceOf(ApiVersion2::class, $client);
    }

    /**
     * @group rest
     */
    public function testVehicleBrandsAll()
    {
        $client = new ApiVersion2(self::CLIENT_SYSTEM_NAME);
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
        $client = new ApiVersion2(self::CLIENT_SYSTEM_NAME);
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

    /**
     * @group soap
     * @group current
     */
    public function testCalcCasco()
    {
        $client = new ApiVersion2(self::CLIENT_SYSTEM_NAME, self::PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $ContractTerm = new \ReninsApi\Request\Soap\ContractTerm();
        $ContractTerm->Product = 1;
        $ContractTerm->ProgramType = 'KASKO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PeriodUseMonth = $ContractTerm->DurationMonth;
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'личная';

        $Covers = new ContainerCollection();
        $Covers->add(new \ReninsApi\Request\Soap\Cover(['code' => 'UGON', 'sum' => 100000]));
        $Covers->add(new \ReninsApi\Request\Soap\Cover(['code' => 'USHERB', 'sum' => 100000]));

        $Vehicle = new \ReninsApi\Request\Soap\Vehicle();
        $Vehicle->Manufacturer = 'ВАЗ';
        $Vehicle->Model = '1117 Kalina';
        $Vehicle->Year = 2013;
        $Vehicle->Cost = 336000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->ManufacturerType = 1;
        $Vehicle->IsNew = true;
        $Vehicle->Power = 98;
        $Vehicle->CarBodyType = 'Седан';

        $Drivers = new \ReninsApi\Request\Soap\Drivers();
        $Drivers->Multidrive = true;
        $Drivers->MinAge = 31;
        $Drivers->MinExperience = 5;

        $Insurant = new \ReninsApi\Request\Soap\Insurant();
        $Insurant->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;

        $Policy = new \ReninsApi\Request\Soap\Policy();
        $Policy->ContractTerm = $ContractTerm;
        $Policy->Covers = $Covers;
        $Policy->Vehicle = $Vehicle;
        $Policy->Participants = $Participants;

        $request = new \ReninsApi\Request\Soap\CalculationCasco();
        $request->genUuid();
        $request->Policy = $Policy;

        $client->calcCasco($request);
    }


}
