<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
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
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'UGON', 'sum' => 300000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'USHERB', 'sum' => 300000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'DO', 'sum' => 100000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'NS', 'sum' => 100000]));

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

        $request = new \ReninsApi\Request\Soap\Calculation\Request();
        $request->type = 1;
        $request->genUuid();
        $request->Policy = $Policy;

        return $request;
    }

    /**
     * @group calculation-casco
     */
    public function testSuccessful()
    {
        $client = $this->createApi2();
        $request = $this->getRequest();

        $response = $client->calc($request);
        //print_r($response->toArray());

        ob_start();
        print_r($response->toArray());
        $data = ob_get_clean();
        @file_put_contents(TEMP_DIR . '/CascoCalcResponse1.txt', $data);

        $this->assertEquals($response->isSuccessful(), true);
        $this->assertGreaterThan(0, $response->CalcResults->count());

        //Получим первый успешный
        $calcResults = $response->getFirstSuccessfulResults();
        $this->assertNotNull($calcResults);
        $this->assertGreaterThan(0, strlen($calcResults->AccountNumber));
        $this->assertEquals('true', $calcResults->Risks->Visible);
        $this->assertEquals('true', $calcResults->Risks->Enabled);
        $this->assertGreaterThan(0, strlen($calcResults->Risks->PacketName));
        $this->assertNotNull($calcResults->Risks->Risk);
        $this->assertGreaterThan(0, $calcResults->Risks->Risk->count());

        @file_put_contents(TEMP_DIR . '/CascoCalcResults1.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта

        @file_put_contents(TEMP_DIR . '/CascoAccountNumber1.txt', $calcResults->AccountNumber); //Номер котировки
    }

    /**
     * @group calculation-casco
     */
    public function testCalcCascoInvalid()
    {
        $client = $this->createApi2();
        $request = $this->getRequest();

        $request->Policy->Vehicle->Manufacturer = 'Here is error';
        $request->Policy->Vehicle->Model = 'Here is error';
        $response = $client->calc($request);
        $this->assertEquals($response->isSuccessful(), false);
    }

}
