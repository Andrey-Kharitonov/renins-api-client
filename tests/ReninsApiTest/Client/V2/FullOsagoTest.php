<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Soap\Printing\DocBytesResponseEx;
use ReninsApiTest\Client\Log;

class FullOsagoTest extends TestCase
{
    use Log;

    private function getDateBegin() {
        return (new \DateTime())->add(new \DateInterval('P1D'))
            ->setTime(12, 0, 0);
    }

    private function getDateEnd() {
        $dtBegin = $this->getDateBegin();
        $dtBegin->add(new \DateInterval('P1Y'))->sub(new \DateInterval('P2D'))->setTime(23, 59, 59);
        return $dtBegin;
    }

    /**
     * Достаточно полная структура по расчету КАСКО. Без франшизы. С лизингом. С телематикой.
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
        $Vehicle->Manufacturer = 'Daewoo';
        $Vehicle->Model = 'Matiz';
        $Vehicle->Year = '2014';
        $Vehicle->Cost = 700000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->AntiTheftDeviceInfo = new \ReninsApi\Request\Soap\Calculation\AntiTheftDeviceInfo([
            'AntiTheftDeviceBrand' => 'Цезарь Сателлит',
            'AntiTheftDeviceModel' => 'Cesar 200',
        ]);
        $Vehicle->PUUDeviceInfo = new \ReninsApi\Request\Soap\Calculation\PUUDeviceInfo([
            'PUUDeviceModel' => 'Sky Brake + доп. блок-р рулевого вала', //SmartCode 2,4-1immo + доп. замок АКПП
        ]);
        $Vehicle->RightWheel = false;
        $Vehicle->ManufacturerType = 0;
        $Vehicle->IsNew = false;
        $Vehicle->IsTaxi = false;
        $Vehicle->Power = 100;
        $Vehicle->GrossWeight = 1500;
        $Vehicle->PassangerCapacity = 4;
        $Vehicle->Category = 'B';
        $Vehicle->CarBodyType = 'Седан';
        $Vehicle->TransmissionType = 'Механическая';
        $Vehicle->EngineType = 'Бензиновый';
        $Vehicle->CarIdent = new \ReninsApi\Request\Soap\Calculation\CarIdent([
            'LicensePlate' => 'Х709НТ179RUS',
        ]);
        $Vehicle->UseTrailer = false;

        $Drivers = new \ReninsApi\Request\Soap\Calculation\Drivers();
        $Drivers->Multidrive = false;
        //$Drivers->Driver здесь указывать не обязательно для этого продукта. Можно указать только в разделе Osago

        $Insurant = new \ReninsApi\Request\Soap\Calculation\Insurant();
        $Insurant->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Calculation\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;
        $Participants->Prospect = new \ReninsApi\Request\Soap\Calculation\Prospect([
            'LastName' => 'Иванова',
            'FirstName' => 'Наталья',
            'MiddleName' => 'Владимировна',
            'Phone' => '+79171450000',
            'Email' => 'abc@abc.ru',
        ]);
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
                        'type' => 'DRIVING_LICENCE',
                        'Serial' => '1255',
                        'Number' => '654321',
                    ])
                ]),
            ])
        ]);
        $Participants->Lessee = new \ReninsApi\Request\Soap\Calculation\Lessee([
            'type' => 2,
            'Account' => new \ReninsApi\Request\Soap\Calculation\Account([
                'Name' => "ООО \"Рога и копыта\"",
                'INN' => '123456789',
            ])
        ]);

        //Product section
        $CalcKBMRequest = new \ReninsApi\Request\Soap\Calculation\CalcKBMRequest();
        $CalcKBMRequest->DateKBM = $dt->format('Y-m-d') . 'T00:00:00';
        $CalcKBMRequest->PhysicalPersons = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\PhysicalPerson([
                'type' => 'driver',
                'DriverExperienceDate' => '1999-06-17',
                'PersonSecondName' => 'Иванов',
                'PersonFirstName' => 'Иван',
                'PersonSurName' => 'Иванович',
                'PersonBirthDate' => '1980-05-12',
                'DriverKbm' => 1.00,
                //'PersonNameBirthHash' => '1980-05-11',
                'DriverDocument' => new \ReninsApi\Request\Soap\Calculation\DriverDocument([
                    'Serial' => '4444',
                    'Number' => '123456',
                ])
            ]),

            new \ReninsApi\Request\Soap\Calculation\PhysicalPerson([
                'type' => 'driver',
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
        //$dtMinusDay = (clone $dt)->sub(new \DateInterval('P1D'));

        $generalQuoteInfo = new \ReninsApi\Request\Soap\Import\GeneralQuoteInfo();
        $generalQuoteInfo->SALE_DATE = $dt->format('Y-m-d') . 'T12:00:00';
        //$generalQuoteInfo->INSURANCE_SUM = '400000'; from CalcResults
        $generalQuoteInfo->CURRENCY = 'RUR';
        //$generalQuoteInfo->PACKET_CALCBASED_ON = '1'; from CalcResults

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
        //$privateQuoteInfo->CREATION_DATE = $dt->format('Y-m-d');
        $privateQuoteInfo->INS_DATE_FROM = $this->getDateBegin()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_FROM = $this->getDateBegin()->format('H:i:s');
        $privateQuoteInfo->INS_DATE_TO = $this->getDateEnd()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_TO = $this->getDateEnd()->format('H:i:s');
        //$privateQuoteInfo->INSURANCE_SUM = '400000'; from CalcResults
        $privateQuoteInfo->CURRENCY = 'RUR';
        $privateQuoteInfo->INS_DURATION = 12;
        $privateQuoteInfo->TOTALLY = false;
        $privateQuoteInfo->PRE_INSURANCE_INSPECTION = new \ReninsApi\Request\Soap\Import\PreInsuranceInspection([
            'NEW_OBJECT' => false,
            'INSPECTION_IS_NEEDED' => true,
            'INSPECTION_NOT_NEEDED_OLD_OBJECT' => false,
        ]);
        $privateQuoteInfo->RISKS = new \ReninsApi\Request\Soap\Import\Risks([
            //'BONUS' => '41553', from CalcResults
            'RISK' => new ContainerCollection([
                //new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'Угон', 'BONUS' => '3387', 'INSURANCE_SUM' => '400000']), from CalcResults
                //new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'ДО', 'BONUS' => '15000', 'INSURANCE_SUM' => '100000']),
                //new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'ДР', 'BONUS' => '185', 'INSURANCE_SUM' => '10000']),
                //new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'НС', 'BONUS' => '350', 'INSURANCE_SUM' => '100000']),
                //new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'Ущерб', 'BONUS' => '22631', 'INSURANCE_SUM' => '400000']),
            ]),
        ]);

        $vehicle = new \ReninsApi\Request\Soap\Import\Vehicle();
        $vehicle->TYPE = 'Легковое ТС';
        $vehicle->BRAND = 'Daewoo';
        $vehicle->MODEL = 'Matiz';
        $vehicle->PRICE = '700000';
        $vehicle->POWER = '100';
        $vehicle->YEAR = '2014';
        $vehicle->VIN = 'AB1CDE23FGH456780';
        $vehicle->REG_SIGN = 'Х709НТ179RUS';
        $vehicle->COLOR = 'Серебристый';
        $vehicle->IS_LEASE = false;
        $vehicle->IS_CREDIT = false;
        $vehicle->PURPOSE = 'личная';
        $vehicle->KEY_COUNT = 2;
        $vehicle->ENGINE_VOLUME = '1600';
        $vehicle->ENGINE_TYPE = 'Бензиновый';
        $vehicle->TRANSMISSION_TYPE = 'МКПП';
        $vehicle->VEHICLE_BODY_TYPE = 'Седан';
        $vehicle->VEHICLE_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PTS',
                'SERIES' => '41НТ',
                'NUMBER' => '123456',
                'ISSUED_DATE' => '2014-02-03',
            ]),
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'TALON_TECHOSMOTR',
                'NUMBER' => '1234567890', //6 или 10 цифр
                'EXPIRE_DATE' => '2018-01-12'
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
            'MULTI_DRIVE' => false,
            'STAFF' => false,

            'DRIVER' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Driver([
                    'CONTACT' => new \ReninsApi\Request\Soap\Import\Contact([
                        'LAST_NAME' => 'Иванов',
                        'FIRST_NAME' => 'Иван',
                        'MIDDLE_NAME' => 'Иванович',
                        'BIRTH_DATE' => '1980-05-12',
                        'DRIVE_EXPERIENCE' => '1999-06-17',
                        'GENDER' => 'М',
                        'MARITAL_STATUS' => 1,
                        'HAS_CHILDREN' => true,
                        'CONTACT_DOCUMENTS' => new ContainerCollection([
                            new \ReninsApi\Request\Soap\Import\Document([
                                'TYPE' => 'DRIVING_LICENCE',
                                'SERIES' => '4444',
                                'NUMBER' => '123456',
                            ])
                        ])
                    ])
                ]),

                new \ReninsApi\Request\Soap\Import\Driver([
                    'CONTACT' => new \ReninsApi\Request\Soap\Import\Contact([
                        'LAST_NAME' => 'Иванова',
                        'FIRST_NAME' => 'Наталья',
                        'MIDDLE_NAME' => 'Владимировна',
                        'BIRTH_DATE' => '1980-06-02',
                        'DRIVE_EXPERIENCE' => '1999-06-17',
                        'GENDER' => 'Ж',
                        'MARITAL_STATUS' => 3,
                        'HAS_CHILDREN' => true,
                        'CONTACT_DOCUMENTS' => new ContainerCollection([
                            new \ReninsApi\Request\Soap\Import\Document([
                                'TYPE' => 'DRIVING_LICENCE',
                                'SERIES' => '1277',
                                'NUMBER' => '654321',
                            ])
                        ])
                    ])
                ])
            ]),
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
        @file_put_contents(TEMP_DIR . '/OsagoCalcResponse2.txt', $data);

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

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults2.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта

        @file_put_contents(TEMP_DIR . '/OsagoAccountNumber2.txt', $calcResults->AccountNumber); //Номер котировки
    }

    /**
     * Печать заявления
     */
    public function testPrePrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber2.txt');
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

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults2-13.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Заявление", pdf
    }

    /**
     * Импорт полиса
     */
    public function testImport()
    {
        $client = $this->createApi2();

        $calcResults = @file_get_contents(TEMP_DIR . '/OsagoCalcResults2.txt');
        if (!$calcResults) {
            throw new \Exception("CalcResults aren't calculated.");
        }
        $calcResults = @unserialize($calcResults);
        if (!is_array($calcResults)) {
            throw new \Exception("CalcResults has invalid format");
        }

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber2.txt');
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

        @file_put_contents(TEMP_DIR . '/OsagoPrintToken2.txt', $response->PrintToken); //Номер, который нужен для окончательной печати полиса
    }

    /**
     * Печать полиса
     */
    public function testFinishPrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber2.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }
        $printToken = @file_get_contents(TEMP_DIR . '/OsagoPrintToken2.txt');
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

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults2-7.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }
}
