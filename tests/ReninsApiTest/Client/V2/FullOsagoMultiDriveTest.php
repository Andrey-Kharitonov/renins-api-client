<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Soap\Printing\DocBytesResponseEx;
use ReninsApiTest\Client\Log;

class FullOsagoMultiDriveTest extends TestCase
{
    use Log;

    /**
     * Дата начала срока действия договора полноценной роли не играет. Датой начала страхования является SALE_DATE.
     * @return \DateTime
     */
    private function getDateBegin() {
        return (new \DateTime())
            ->setTime(12, 0, 0);
    }

    /**
     * Поскольку ОСАГО считает датой начала страхования SALE_DATE, то здесь должно быть + 1 год - 1 день относительно SALE_DATE
     * @return \DateTime
     */
    private function getDateEnd() {
        $dtBegin = $this->getDateBegin();
        $dtBegin->add(new \DateInterval('P1Y'))->sub(new \DateInterval('P1D'))->setTime(23, 59, 59);
        return $dtBegin;
    }

    /**
     * Мультидрайв
     * @return \ReninsApi\Request\Soap\Calculation\Request
     */
    private function getCalcRequest() {
        $dt = new \DateTime();

        $ContractTerm = new \ReninsApi\Request\Soap\Calculation\ContractTerm();
        $ContractTerm->Product = 1;
        $ContractTerm->ProgramType = 'OSAGO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PeriodUseMonth = $ContractTerm->DurationMonth;
        $ContractTerm->Periods = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\Period([
                'UseDateBegin' => $this->getDateBegin()->format('Y-m-d'),
                'UseDateEnd' => $this->getDateEnd()->format('Y-m-d')
            ])
        ]);
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'личная';

        $Vehicle = new \ReninsApi\Request\Soap\Calculation\Vehicle();
        $Vehicle->Manufacturer = 'ВАЗ';
        $Vehicle->Model = 'Vesta';
        $Vehicle->Year = '2016';
        $Vehicle->Cost = 500000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->ManufacturerType = 0;
        $Vehicle->Power = 120;
        $Vehicle->CarBodyType = 'Седан';
        $Vehicle->TransmissionType = 'Механическая';
        $Vehicle->EngineType = 'Бензиновый';
        $Vehicle->CarIdent = new \ReninsApi\Request\Soap\Calculation\CarIdent([
            'LicensePlate' => 'Х777НТ179RUS',
            'VIN' => 'AB1C3F3F5GH456780',
            'BodyNumber' => 'G23847343JH334',
            'ChassisNumber' => 'D23847343JH334',
        ]);

        $Drivers = new \ReninsApi\Request\Soap\Calculation\Drivers();
        $Drivers->Multidrive = true;
        $Drivers->MinAge = 35;
        $Drivers->MinExperience = 15;

        $Insurant = new \ReninsApi\Request\Soap\Calculation\Insurant();
        $Insurant->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Calculation\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;
        $Participants->Owner = new \ReninsApi\Request\Soap\Calculation\Owner([
            'type' => 1,
            'Contact' => new \ReninsApi\Request\Soap\Calculation\Contact([
                'LastName' => 'Иванова',
                'FirstName' => 'Наталья',
                'MiddleName' => 'Владимировна',
                'BirthDate' => '1980-06-01',
                'DriveExperience' => '2001-01-01',
                'Gender' => 'Ж',
                'MaritalStatus' => 3,
                'HasChildren' => true,
                'Documents' => new ContainerCollection([
                    new \ReninsApi\Request\Soap\Calculation\Document([
                        'type' => 'PASSPORT',
                        'Serial' => '1234',
                        'Number' => '123456',
                    ])
                ]),
            ])
        ]);

        //Product section
        $CalcKBMRequest = new \ReninsApi\Request\Soap\Calculation\CalcKBMRequest();
        $CalcKBMRequest->DateKBM = $dt->format('Y-m-d') . 'T00:00:00';
        $CalcKBMRequest->PhysicalPersons = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\PhysicalPerson([
                'type' => 'owner',
                'DriverExperienceDate' => '1999-06-17',
                'PersonSecondName' => 'Иванова',
                'PersonFirstName' => 'Наталья',
                'PersonSurName' => 'Владимировна',
                'PersonBirthDate' => '1980-06-02',
                'DriverKbm' => 1.00,
                //'PersonNameBirthHash' => '1980-05-11',
                'DriverDocument' => new \ReninsApi\Request\Soap\Calculation\DriverDocument([
                    'Serial' => '1277',
                    'Number' => '654321',
                ]),
                'PreviousData' => new \ReninsApi\Request\Soap\Calculation\PreviousDataPh([
                    'DriverDocument' => new \ReninsApi\Request\Soap\Calculation\DriverDocument([
                        'Serial' => '125411',
                        'Number' => '0987611',
                    ]),
                    'PersonSecondName' => 'Сидорова',
                    'PersonFirstName' => 'Наталья',
                    'PersonSurName' => 'Владимировна',
                ])
            ])
        ]);

        $Osago = new \ReninsApi\Request\Soap\Calculation\Osago();
        $Osago->CalculationType = 1;
        $Osago->RegistrationPlace = 'Москва';
        $Osago->Kbm = 1.00;
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
     * @return \ReninsApi\Request\Soap\Import\InputMessage
     */
    private function getImportRequest() {
        $dt = new \DateTime();

        $generalQuoteInfo = new \ReninsApi\Request\Soap\Import\GeneralQuoteInfo();
        $generalQuoteInfo->SALE_DATE = $this->getDateBegin()->format('Y-m-d') . 'T12:00:00';
        $generalQuoteInfo->CURRENCY = 'RUR';

        $partner = new \ReninsApi\Request\Soap\Import\Partner();
        $partner->NAME = 'Link_Auto';
        $partner->DEPARTMENT = '001';
        $partner->DIVISION = 'Москва';
        $partner->FILIAL = 'Москва';

        $seller = new \ReninsApi\Request\Soap\Import\Seller();
        $seller->TYPE = 'PARTNER';
        $seller->PARTNER = $partner;
        $seller->MANAGERS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Manager(['name' => 'Иванов И.И.']),
        ]);

        $contact = new \ReninsApi\Request\Soap\Import\Contact();
        $contact->IPFLAG = false;
        $contact->HOME_PHONE = '+74957654321';
        $contact->CELL_PHONE = '+74957654321';
        $contact->LAST_NAME = 'Иванова';
        $contact->FIRST_NAME = 'Наталья';
        $contact->MIDDLE_NAME = 'Владимировна';
        $contact->BIRTH_DATE = '1980-06-02';
        $contact->RESIDENT = true;
        $contact->CONTACT_ADDRESSES = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Address([
                'TYPE' => 'ADDR_CON_REG',
                'COUNTRY' => 'Российская Федерация',
                'CITY' => 'Москва',
                'STREET' => 'Льва Толстого',
                'HOUSE' => '16',
                'APPARTMENT' => '1',
                'POSTAL_CODE' => '119021',
            ]),
        ]);
        $contact->CONTACT_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PASSPORT',
                'SERIES' => '1234',
                'NUMBER' => '123456',
                'ISSUED_DATE' => '1998-01-01',
                'ISSUED_WHERE' => 'РОВД',
            ])
        ]);

        $insurant = new \ReninsApi\Request\Soap\Import\ContactInfo();
        $insurant->TYPE = 'CONTACT';
        $insurant->CONTACT = $contact;

        $privateQuoteInfo = new \ReninsApi\Request\Soap\Import\PrivateQuoteInfo();
        $privateQuoteInfo->DOCUMENT_OF_PAYMENT = new \ReninsApi\Request\Soap\Import\DocumentOfPayment([
            'TYPE' => 'по квитанции СБЕРБАНКА',
            'PAY_DOC_NUMBER' => '0123456789',
            'PAY_DOC_ISSUE_DATE' => $dt->format('Y-m-d'),
        ]);
        $privateQuoteInfo->POLICY_NUMBER = mt_rand(10000, 99999) . mt_rand(10000, 99999);
        $privateQuoteInfo->BSO_NUMBER = '1234567';
        $privateQuoteInfo->INS_DATE_FROM = $this->getDateBegin()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_FROM = $this->getDateBegin()->format('H:i:s');
        $privateQuoteInfo->INS_DATE_TO = $this->getDateEnd()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_TO = $this->getDateEnd()->format('H:i:s');
        $privateQuoteInfo->CURRENCY = 'RUR';
        $privateQuoteInfo->INS_DURATION = 12;
        $privateQuoteInfo->TOTALLY = false;
        $privateQuoteInfo->RISKS = new \ReninsApi\Request\Soap\Import\Risks([
            'RISK' => new ContainerCollection([
            ]),
        ]);

        $vehicle = new \ReninsApi\Request\Soap\Import\Vehicle();
        $vehicle->TYPE = 'Легковое ТС';
        $vehicle->BRAND = 'ВАЗ';
        $vehicle->MODEL = 'Vesta';
        $vehicle->PRICE = '500000';
        $vehicle->POWER = '120';
        $vehicle->YEAR = '2016';
        $vehicle->VIN = 'AB1C3F3F5GH456780';
        $vehicle->REG_SIGN = 'Х777НТ179RUS';
        $vehicle->COLOR = 'Розовый';
        $vehicle->PURPOSE = 'личная';
        $vehicle->KEY_COUNT = 2;
        $vehicle->ENGINE_VOLUME = '1800';
        $vehicle->ENGINE_TYPE = 'Бензиновый';
        $vehicle->TRANSMISSION_TYPE = 'МКПП';
        $vehicle->VEHICLE_BODY_TYPE = 'Седан';
        $vehicle->VEHICLE_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PTS',
                'SERIES' => '4356',
                'NUMBER' => '235674',
                'ISSUED_DATE' => '2017-02-03',
            ]),
        ]);

        $owner = new \ReninsApi\Request\Soap\Import\Owner();
        $owner->TYPE = 'CONTACT';
        $owner->CONTACT = $contact;

        $context = new \ReninsApi\Request\Soap\Import\Context();
        $context->PRIVATE_QUOTE_INFO = $privateQuoteInfo;
        $context->VEHICLE = $vehicle;
        $context->OWNER = $owner;
        $context->BENEFICIARIES = new \ReninsApi\Request\Soap\Import\Beneficiaries([
            'BENEFICIARY' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Beneficiary([
                    'TYPE' => 'CONTACT',
                    'CONTACT' => $contact,
                ]),
            ]),
        ]);
        $context->DRIVERS = new \ReninsApi\Request\Soap\Import\Drivers([
            'MULTI_DRIVE' => true,
            'MIN_AGE' => 35,
            'MIN_EXPERIENCE' => 15,
        ]);

        $inputMessage = new \ReninsApi\Request\Soap\Import\InputMessage();
        $inputMessage->GENERAL_QUOTE_INFO = $generalQuoteInfo;
        $inputMessage->SELLER = $seller;
        $inputMessage->INSURANT = $insurant;
        $inputMessage->LIST_OF_CONTEXTS = new ContainerCollection([
            $context
        ]);

        return $inputMessage;
    }

    /**
     * Расчет с созданием котировки
     */
    public function testCalc()
    {
        $client = $this->createApi2();
        $request = $this->getCalcRequest();

        $response = $client->calc($request);

        ob_start();
        print_r($response->toArray());
        $data = ob_get_clean();
        @file_put_contents(TEMP_DIR . '/OsagoCalcResponse3.txt', $data);

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

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults3.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта

        @file_put_contents(TEMP_DIR . '/OsagoAccountNumber3.txt', $calcResults->AccountNumber); //Номер котировки
    }

    /**
     * Печать заявления
     */
    public function testPrePrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber3.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new \ReninsApi\Request\Soap\Printing\PrintingParams(['DocumentTypeId' => 13]),
        ]);

        $response = $client->printDocumentsToBinary($param);

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        /* @var DocBytesResponseEx $docBytesResponseEx */
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(true, $docBytesResponseEx->Success);
        $this->assertNotNull($docBytesResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults3-13.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Заявление", pdf
    }

    /**
     * Импорт полиса
     */
    public function testImport()
    {
        $client = $this->createApi2();

        $calcResults = @file_get_contents(TEMP_DIR . '/OsagoCalcResults3.txt');
        if (!$calcResults) {
            throw new \Exception("CalcResults aren't calculated.");
        }
        $calcResults = @unserialize($calcResults);
        if (!is_array($calcResults)) {
            throw new \Exception("CalcResults has invalid format");
        }

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber3.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }

        $request = $this->getImportRequest();
        $request->GENERAL_QUOTE_INFO->ACCOUNT_NUMBER_CALCBASED_ON = $accountNumber;
        $request->GENERAL_QUOTE_INFO->PACKET_CALCBASED_ON = $calcResults['Risks']['PacketName'];
        $request->GENERAL_QUOTE_INFO->INSURANCE_SUM = $calcResults['Total']['Sum'];

        /* @var \ReninsApi\Request\Soap\Import\Context $context */
        $context = $request->LIST_OF_CONTEXTS->get(0);
        $context->PRIVATE_QUOTE_INFO->INSURANCE_SUM = $calcResults['Total']['Sum'];

        $context->PRIVATE_QUOTE_INFO->RISKS->BONUS = $calcResults['Total']['Bonus'];
        foreach ($calcResults['Risks']['Risk'] as $risk) {
            $riskObj = new \ReninsApi\Request\Soap\Import\Risk();
            $riskObj->NAME = $risk['Name'];
            $riskObj->BONUS = $risk['Bonus'];
            $riskObj->INSURANCE_SUM = $risk['Sum'];
            $context->PRIVATE_QUOTE_INFO->RISKS->RISK->add($riskObj);
        }

        $response = $client->ImportPolicy($request);
        //print_r($response->toArray());

        $this->assertGreaterThan(0, strlen($response->PolicyId));
        $this->assertGreaterThan(0, strlen($response->AccountNumber));
        $this->assertGreaterThan(0, strlen($response->PrintToken));
        $this->assertGreaterThan(0, $response->AvailableDocumentTypes->count());

        @file_put_contents(TEMP_DIR . '/OsagoPrintToken3.txt', $response->PrintToken); //Номер, который нужен для окончательной печати полиса
    }

    /**
     * Печать полиса
     */
    public function testFinishPrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber3.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }
        $printToken = @file_get_contents(TEMP_DIR . '/OsagoPrintToken3.txt');
        if (!$printToken) {
            throw new \Exception("PrintToken isn't calculated.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new \ReninsApi\Request\Soap\Printing\PrintingParams(['DocumentTypeId' => 7]),
        ]);
        $param->PrintToken = $printToken;

        $response = $client->printDocumentsToBinary($param);

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        /* @var DocBytesResponseEx $docBytesResponseEx */
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(true, $docBytesResponseEx->Success);
        $this->assertNotNull($docBytesResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults3-7.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }
}
