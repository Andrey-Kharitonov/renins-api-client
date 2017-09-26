<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Request\ContainerCollection;
use ReninsApiTest\Client\Log;

class CalcCascoTest extends TestCase
{
    use Log;

    private function getRequest() {
        $ContractTerm = new \ReninsApi\Request\Soap\Calculation\ContractTerm();
        $ContractTerm->Product = 1;
        $ContractTerm->ProgramType = 'KASKO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PeriodUseMonth = $ContractTerm->DurationMonth;
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'личная';

        $Covers = new ContainerCollection();
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'UGON', 'sum' => 100000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'USHERB', 'sum' => 100000]));

        $Vehicle = new \ReninsApi\Request\Soap\Calculation\Vehicle();
        $Vehicle->Manufacturer = 'ВАЗ';
        $Vehicle->Model = '1117 Kalina';
        $Vehicle->Year = date('Y');
        $Vehicle->Cost = 400000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->ManufacturerType = 1;
        $Vehicle->IsNew = true;
        $Vehicle->Power = 98;
        $Vehicle->CarBodyType = 'Седан';

        $Drivers = new \ReninsApi\Request\Soap\Calculation\Drivers();
        $Drivers->Multidrive = true;
        $Drivers->MinAge = 31;
        $Drivers->MinExperience = 5;

        $Insurant = new \ReninsApi\Request\Soap\Calculation\Insurant();
        $Insurant->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Calculation\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;

        //Product section
        $Stoa = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\StoaType(['type' => 3, 'enabled' => false]),
            new \ReninsApi\Request\Soap\Calculation\StoaType(['type' => 2, 'enabled' => true]),
            //new \ReninsApi\Request\Soap\Calculation\StoaType(['type' => 5, 'enabled' => true]),
        ]);

        $Casco = new \ReninsApi\Request\Soap\Calculation\Casco();
        $Casco->Stoa = $Stoa;
        //---

        $Policy = new \ReninsApi\Request\Soap\Calculation\Policy();
        $Policy->ContractTerm = $ContractTerm;
        $Policy->Covers = $Covers;
        $Policy->Vehicle = $Vehicle;
        $Policy->Participants = $Participants;
        $Policy->Casco = $Casco;

        $request = new \ReninsApi\Request\Soap\Calculation\CalculationCasco();
        $request->type = 1;
        $request->genUuid();
        $request->Policy = $Policy;

        return $request;
    }

    /**
     * @group calculation
     * @group casco
     */
    public function testSuccessful()
    {
        $client = $this->createApi2();
        $request = $this->getRequest();

        $response = $client->calcCasco($request);
        $this->assertInstanceOf(\ReninsApi\Response\Soap\Calculation\MakeCalculationResult::class, $response);
        $this->assertEquals($response->isSuccessful(), true);
        $this->assertInstanceOf(ContainerCollection::class, $response->CalcResults);
        $this->assertGreaterThan(0, $response->CalcResults->count());
        $calcResults = $response->CalcResults->get(0);
        $this->assertInstanceOf(\ReninsApi\Response\Soap\Calculation\CalcResults::class, $calcResults);

        @file_put_contents(TEMP_DIR . '/CascoAccountNumber1.txt', $calcResults->AccountNumber); //Просто расчет
    }

    /**
     * @group calculation
     * @group casco
     */
    public function testCalcCascoInvalid()
    {
        $client = $this->createApi2();
        $request = $this->getRequest();

        $request->Policy->Vehicle->Manufacturer = 'Here is error';
        $request->Policy->Vehicle->Model = 'Here is error';
        $response = $client->calcCasco($request);
        $this->assertInstanceOf(\ReninsApi\Response\Soap\Calculation\MakeCalculationResult::class, $response);
        $this->assertEquals($response->isSuccessful(), false);
    }

}
