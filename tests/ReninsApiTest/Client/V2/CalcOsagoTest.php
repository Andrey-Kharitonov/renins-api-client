<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\CarIdent;
use ReninsApiTest\Client\Log;

class CalcOsagoTest extends TestCase
{
    use Log;

    private function getRequest() {
        $ContractTerm = new \ReninsApi\Request\Soap\Calculation\ContractTerm();
        $ContractTerm->Product = 3;
        $ContractTerm->ProgramType = 'OSAGO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'личная';

        $Vehicle = new \ReninsApi\Request\Soap\Calculation\Vehicle();
        $Vehicle->Manufacturer = 'Daewoo';
        $Vehicle->Model = 'Matiz';
        $Vehicle->Year = '2014';
        $Vehicle->Cost = 700000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->ManufacturerType = 0;
        $Vehicle->Power = 100;
        $Vehicle->CarBodyType = 'Седан';
        $Vehicle->CarIdent = new CarIdent([
            'LicensePlate' => 'Х709НТ179RUS',
        ]);

        $Drivers = new \ReninsApi\Request\Soap\Calculation\Drivers();
        $Drivers->type = 1;
        $Drivers->Multidrive = false;
        /*
        $Drivers->Driver = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\Contact([
                'LastName' => 'Иванов',
                'FirstName' => 'Иван',
                'BirthDate' => '1980-05-11',
                'DriveExperience' => '2001-01-01',
                'Documents' => new ContainerCollection([
                    new \ReninsApi\Request\Soap\Calculation\Document([
                        'type' => 'DRIVING_LICENCE',
                        'Serial' => '1234',
                        'Number' => '123456',
                    ])
                ]),
            ])
        ]);
        */

        $Insurant = new \ReninsApi\Request\Soap\Calculation\Insurant();
        $Insurant->type = 1;

        $Owner = new \ReninsApi\Request\Soap\Calculation\Owner();
        $Owner->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Calculation\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;
        $Participants->Owner = $Owner;

        //Product section
        $CalcKBMRequest = new \ReninsApi\Request\Soap\Calculation\CalcKBMRequest();
        $CalcKBMRequest->DateKBM = '2017-01-13T00:00:00';
        $CalcKBMRequest->PhysicalPersons = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\PhysicalPerson([
                'type' => 'driver',
                'DriverExperienceDate' => '1999-06-17',
                'PersonSecondName' => 'Иванов',
                'PersonFirstName' => 'Иван',
                'PersonSurName' => 'Иванович',
                'PersonBirthDate' => '1980-05-11',
                'DriverDocument' => new \ReninsApi\Request\Soap\Calculation\DriverDocument([
                    'Serial' => '125411',
                    'Number' => '0987611',
                ]),
            ])
        ]);

        $Osago = new \ReninsApi\Request\Soap\Calculation\Osago();
        $Osago->CalculationType = 1;
        $Osago->RegistrationPlace = 'Москва';
        $Osago->Kbm = 2.45;
        $Osago->SeasonUse = false;
        $Osago->RegistrationCountry = 'Россия';
        $Osago->Transit = false;
        $Osago->KNEnable = false;
        $Osago->CalcKBMRequest = $CalcKBMRequest;
        //---

        $Policy = new \ReninsApi\Request\Soap\Calculation\Policy();
        $Policy->ContractTerm = $ContractTerm;
        $Policy->Vehicle = $Vehicle;
        $Policy->Participants = $Participants;
        $Policy->Osago = $Osago;

        $request = new \ReninsApi\Request\Soap\Calculation\Request();
        $request->type = 1;
        $request->genUuid();
        $request->Policy = $Policy;

        return $request;
    }

    /**
     * ОСАГО ВИ (на ЛДУ), ФЛ, ЛДУ (расчет, запрос).xml
     * @group calculation-osago
     */
    public function testSuccessful()
    {
        $client = $this->createApi2();
        $request = $this->getRequest();

        $response = $client->calc($request);

        ob_start();
        print_r($response->toArray());
        $data = ob_get_clean();
        @file_put_contents(TEMP_DIR . '/OsagoCalcResponse1.txt', $data);

        //Получим первый успешный
        $calcResults = $response->getFirstSuccessfulResults();
        $this->assertNotNull($calcResults);
        $this->assertGreaterThan(0, strlen($calcResults->AccountNumber));
        $this->assertEquals('true', $calcResults->Risks->Visible);
        $this->assertEquals('true', $calcResults->Risks->Enabled);
        $this->assertGreaterThan(0, strlen($calcResults->Risks->PacketName));
        $this->assertNotNull($calcResults->Risks->Risk);
        $this->assertGreaterThan(0, $calcResults->Risks->Risk->count());

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults1.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта

        @file_put_contents(TEMP_DIR . '/OsagoAccountNumber1.txt', $calcResults->AccountNumber); //Номер котировки
    }

    /**
     * @group calculation-osago
     */
    public function testInvalid()
    {
        $client = $this->createApi2();
        $request = $this->getRequest();

        $request->Policy->Osago->CalcKBMRequest->DateKBM = '2040-01-01T12:00:00'; //Here is error
        $response = $client->calc($request);
        $this->assertEquals($response->isSuccessful(), false);
    }

}
