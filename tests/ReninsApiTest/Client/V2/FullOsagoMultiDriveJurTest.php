<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Soap\Printing\DocBytesResponseEx;
use ReninsApiTest\Client\Log;

class FullOsagoMultiDriveJurTest extends TestCase
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
     * Мультидрайв, страхователь - юр. лицо
     * @return \ReninsApi\Request\Soap\Calculation\Request
     */
    private function getCalcRequest() {
        $dt = new \DateTime();

        $ContractTerm = new \ReninsApi\Request\Soap\Calculation\ContractTerm();
        $ContractTerm->Product = 1;
        $ContractTerm->ProgramType = 'OSAGO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PeriodUseMonth = $ContractTerm->DurationMonth;
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'прочее';

        $Vehicle = new \ReninsApi\Request\Soap\Calculation\Vehicle();
        $Vehicle->Manufacturer = 'УАЗ';
        $Vehicle->Model = '3163 Patriot';
        $Vehicle->Year = '2016';
        $Vehicle->Cost = 700000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->ManufacturerType = 0;
        $Vehicle->Power = 180;
        $Vehicle->CarBodyType = 'Другой тип кузова';
        $Vehicle->TransmissionType = 'Механическая';
        $Vehicle->EngineType = 'Дизельный';
        $Vehicle->CarIdent = new \ReninsApi\Request\Soap\Calculation\CarIdent([
            'LicensePlate' => 'О888ОО82RUS',
            'VIN' => 'CC1C3F3F5GH456780',
            'BodyNumber' => 'DD3847343JH334',
            'ChassisNumber' => 'FF3847343JH334',
        ]);

        $Drivers = new \ReninsApi\Request\Soap\Calculation\Drivers();
        $Drivers->Multidrive = true;
        $Drivers->MinAge = 40;
        $Drivers->MinExperience = 20;

        $Insurant = new \ReninsApi\Request\Soap\Calculation\Insurant();
        $Insurant->type = 2;

        $Participants = new \ReninsApi\Request\Soap\Calculation\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;
        $Participants->Owner = new \ReninsApi\Request\Soap\Calculation\Owner([
            'type' => 2,
            'Account' => new \ReninsApi\Request\Soap\Calculation\Account([
                'Name' => 'ООО "РосНАНО"',
                'INN' => '7728864753',
            ])
        ]);

        //Product section
        $CalcKBMRequest = new \ReninsApi\Request\Soap\Calculation\CalcKBMRequest();
        $CalcKBMRequest->DateKBM = $dt->format('Y-m-d') . 'T00:00:00';
        $CalcKBMRequest->JuridicalPerson = new \ReninsApi\Request\Soap\Calculation\JuridicalPerson([
            'OrgName' => 'Общество с ограниченной ответственностью "РосНАНО"',
            'OrgINN' => '7728864753',
            'OPF' => 'ООО',
            'OrgKbm' => 1.0,
            'PreviousData' => new \ReninsApi\Request\Soap\Calculation\PreviousDataJur([
                'OrgName' => 'Рога и копыта',
                'OrgINN' => '7728864754',
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
        $dtMinusDay = (clone $dt)->sub(new \DateInterval('P1D'));

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

        $account = new \ReninsApi\Request\Soap\Import\Account();
        $account->DOCUMENT = new \ReninsApi\Request\Soap\Import\Document([
            'TYPE' => 'REGISTRATION_CERTIFICATE',
            'SERIES' => '54', //В КАСКО это не проверяется, а здесь проверяется
            'NUMBER' => '2342332',
            'ISSUED_DATE' => '1998-10-10',
        ]);
        $account->NAME = 'РосНАНО';
        $account->OPF = 'ООО';
        $account->FTN = '7728864753';
        $account->EMAIL = 'abc@rosnano.ru';
        $account->MAIN_PHONE = '+71234567890';
        $account->RESIDENT = true;
        $account->ACCOUNT_ADDRESSES = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Address([
                'TYPE' => 'ADDR_ACNT_JUR',
                'COUNTRY' => 'Российская Федерация',
                'CITY' => 'Москва',
                'STREET' => 'Льва Яшина ул.',
                'HOUSE' => '1',
                'APPARTMENT' => '123',
            ]),
        ]);

        $insurant = new \ReninsApi\Request\Soap\Import\ContactInfo();
        $insurant->TYPE = 'ACCOUNT';
        $insurant->ACCOUNT = $account;

        $privateQuoteInfo = new \ReninsApi\Request\Soap\Import\PrivateQuoteInfo();
        $privateQuoteInfo->DOCUMENT_OF_PAYMENT = new \ReninsApi\Request\Soap\Import\DocumentOfPayment([
            'TYPE' => 'по квитанции А7',
            'PAY_DOC_NUMBER' => '2342342333',
            'PAY_DOC_ISSUE_DATE' => $dtMinusDay->format('Y-m-d'),
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
        $vehicle->BRAND = 'УАЗ';
        $vehicle->MODEL = '3163 Patriot';
        $vehicle->PRICE = '700000';
        $vehicle->POWER = '180';
        $vehicle->YEAR = '2016';
        $vehicle->VIN = 'CC1C3F3F5GH456780';
        $vehicle->REG_SIGN = 'О888ОО82RUS';
        $vehicle->COLOR = 'Черный';
        $vehicle->PURPOSE = 'прочее';
        $vehicle->KEY_COUNT = 10;
        $vehicle->ENGINE_VOLUME = '2200';
        $vehicle->ENGINE_TYPE = 'Дизельный';
        $vehicle->TRANSMISSION_TYPE = 'МКПП';
        $vehicle->VEHICLE_BODY_TYPE = 'Внедорожник';
        $vehicle->VEHICLE_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PTS',
                'SERIES' => '5765',
                'NUMBER' => '855675',
                'ISSUED_DATE' => '2016-02-03',
            ]),
        ]);

        $owner = new \ReninsApi\Request\Soap\Import\Owner();
        $owner->TYPE = 'ACCOUNT';
        $owner->ACCOUNT = $account;

        $context = new \ReninsApi\Request\Soap\Import\Context();
        $context->PRIVATE_QUOTE_INFO = $privateQuoteInfo;
        $context->VEHICLE = $vehicle;
        $context->OWNER = $owner;
        $context->BENEFICIARIES = new \ReninsApi\Request\Soap\Import\Beneficiaries([
            'BENEFICIARY' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Beneficiary([
                    'TYPE' => 'ACCOUNT',
                    'ACCOUNT' => $account,
                ]),
            ]),
        ]);
        $context->DRIVERS = new \ReninsApi\Request\Soap\Import\Drivers([
            'MULTI_DRIVE' => true,
            'MIN_AGE' => 40,
            'MIN_EXPERIENCE' => 20,
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
        @file_put_contents(TEMP_DIR . '/OsagoCalcResponse4.txt', $data);

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

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults4.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта

        @file_put_contents(TEMP_DIR . '/OsagoAccountNumber4.txt', $calcResults->AccountNumber); //Номер котировки
    }

    /**
     * Печать заявления
     */
    public function testPrePrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber4.txt');
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

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults4-13.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Заявление", pdf
    }

    /**
     * Импорт полиса
     */
    public function testImport()
    {
        $client = $this->createApi2();

        $calcResults = @file_get_contents(TEMP_DIR . '/OsagoCalcResults4.txt');
        if (!$calcResults) {
            throw new \Exception("CalcResults aren't calculated.");
        }
        $calcResults = @unserialize($calcResults);
        if (!is_array($calcResults)) {
            throw new \Exception("CalcResults has invalid format");
        }

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber4.txt');
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

        @file_put_contents(TEMP_DIR . '/OsagoPrintToken4.txt', $response->PrintToken); //Номер, который нужен для окончательной печати полиса
    }

    /**
     * Печать полиса
     */
    public function testFinishPrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/OsagoAccountNumber4.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }
        $printToken = @file_get_contents(TEMP_DIR . '/OsagoPrintToken4.txt');
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

        @file_put_contents(TEMP_DIR . '/OsagoCalcResults4-7.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }
}
