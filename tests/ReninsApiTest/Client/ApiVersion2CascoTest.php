<?php

namespace ReninsApiTest\Client;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Request\ContainerCollection;

class ApiVersion2CascoTest extends TestCase
{
    public function onLog(LogEvent $event) {
        global $loggerCalc;
        $loggerCalc->info("{$event->method}: {$event->message}", $event->data);
    }

    private function getCascoRequest() {
        $ContractTerm = new \ReninsApi\Request\Soap\Calc\ContractTerm();
        $ContractTerm->Product = 1;
        $ContractTerm->ProgramType = 'KASKO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PeriodUseMonth = $ContractTerm->DurationMonth;
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'личная';

        $Covers = new ContainerCollection();
        $Covers->add(new \ReninsApi\Request\Soap\Calc\Cover(['code' => 'UGON', 'sum' => 100000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calc\Cover(['code' => 'USHERB', 'sum' => 100000]));

        $Vehicle = new \ReninsApi\Request\Soap\Calc\Vehicle();
        $Vehicle->Manufacturer = 'ВАЗ';
        $Vehicle->Model = '1117 Kalina';
        $Vehicle->Year = date('Y');
        $Vehicle->Cost = 400000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->ManufacturerType = 1;
        $Vehicle->IsNew = true;
        $Vehicle->Power = 98;
        $Vehicle->CarBodyType = 'Седан';

        $Drivers = new \ReninsApi\Request\Soap\Calc\Drivers();
        $Drivers->Multidrive = true;
        $Drivers->MinAge = 31;
        $Drivers->MinExperience = 5;

        $Insurant = new \ReninsApi\Request\Soap\Calc\Insurant();
        $Insurant->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Calc\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;

        //Product section
        $Stoa = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calc\StoaType(['type' => 3, 'enabled' => false]),
            new \ReninsApi\Request\Soap\Calc\StoaType(['type' => 2, 'enabled' => true]),
            //new \ReninsApi\Request\Soap\Calc\StoaType(['type' => 5, 'enabled' => true]),
        ]);

        $Casco = new \ReninsApi\Request\Soap\Calc\Casco();
        $Casco->Stoa = $Stoa;
        //---

        $Policy = new \ReninsApi\Request\Soap\Calc\Policy();
        $Policy->ContractTerm = $ContractTerm;
        $Policy->Covers = $Covers;
        $Policy->Vehicle = $Vehicle;
        $Policy->Participants = $Participants;
        $Policy->Casco = $Casco;

        $request = new \ReninsApi\Request\Soap\Calc\CalculationCasco();
        $request->genUuid();
        $request->Policy = $Policy;

        return $request;
    }

    /**
     * @group soap
     */
    public function testCalcCascoSuccessful()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $request = $this->getCascoRequest();

        $response = $client->calcCasco($request);
        //print_r($response->toArray());
        $this->assertInstanceOf(\ReninsApi\Response\Soap\MakeCalculationResult::class, $response);
        $this->assertEquals($response->isSuccessful(), true);
    }

    /**
     * @group soap
     */
    public function testCalcCascoInvalid()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $request = $this->getCascoRequest();
        $request->Policy->Vehicle->Manufacturer = 'Here is error';
        $request->Policy->Vehicle->Model = 'Here is error';

        $response = $client->calcCasco($request);
        //print_r($response->toArray());
        $this->assertInstanceOf(\ReninsApi\Response\Soap\MakeCalculationResult::class, $response);
        $this->assertEquals($response->isSuccessful(), false);
    }

}
