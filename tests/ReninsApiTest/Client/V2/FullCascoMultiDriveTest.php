<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Soap\Printing\DocBytesResponseEx;
use ReninsApiTest\Client\Log;

class FullCascoMultiDriveTest extends TestCase
{
    use Log;

    private function getDateBegin() {
        return (new \DateTime())->add(new \DateInterval('P2D'))
            ->setTime(12, 0, 0);
    }

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
        $ContractTerm = new \ReninsApi\Request\Soap\Calculation\ContractTerm();
        $ContractTerm->Product = 1;
        $ContractTerm->ProgramType = 'KASKO';
        $ContractTerm->DurationMonth = 12;
        $ContractTerm->PeriodUseMonth = $ContractTerm->DurationMonth;
        $ContractTerm->PaymentType = 1;
        $ContractTerm->Purpose = 'личная';

        $Covers = new ContainerCollection();
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'USHERB', 'sum' => 450000]));
        $Covers->add(new \ReninsApi\Request\Soap\Calculation\Cover(['code' => 'NS', 'sum' => 100000]));

        $Vehicle = new \ReninsApi\Request\Soap\Calculation\Vehicle();
        $Vehicle->Manufacturer = 'ВАЗ';
        $Vehicle->Model = 'Granta';
        $Vehicle->Year = date('Y');
        $Vehicle->Cost = 450000;
        $Vehicle->Type = 'Легковое ТС';
        $Vehicle->ManufacturerType = 1;
        $Vehicle->IsNew = true;
        $Vehicle->IsTaxi = false;
        $Vehicle->Power = 98;
        $Vehicle->CarBodyType = 'Седан';
        $Vehicle->TransmissionType = 'Механическая';
        $Vehicle->EngineType = 'Бензиновый';
        $Vehicle->CarIdent = new \ReninsApi\Request\Soap\Calculation\CarIdent([
            'LicensePlate' => 'У132ЕР163',
        ]);

        $Drivers = new \ReninsApi\Request\Soap\Calculation\Drivers();
        $Drivers->Multidrive = true;
        $Drivers->MinExperience = 10;
        $Drivers->MinAge = 35;

        $Insurant = new \ReninsApi\Request\Soap\Calculation\Insurant();
        $Insurant->type = 1;

        $Participants = new \ReninsApi\Request\Soap\Calculation\Participants();
        $Participants->Drivers = $Drivers;
        $Participants->Insurant = $Insurant;
        $Participants->BeneficiaryType = 1;

        //Product section
        $Stoa = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\StoaType(['type' => 2, 'enabled' => true]),
        ]);

        $Casco = new \ReninsApi\Request\Soap\Calculation\Casco();
        $Casco->Stoa = $Stoa;
        $Casco->CustomOptions = new ContainerCollection([
            new \ReninsApi\Request\Soap\Calculation\Option([
                'type' => 1,
                'enabled' => true,
            ]),
            new \ReninsApi\Request\Soap\Calculation\Option([
                'type' => 3,
                'enabled' => true,
            ])
        ]);
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
     * @return \ReninsApi\Request\Soap\Import\InputMessage
     */
    private function getImportRequest() {
        $dt = new \DateTime();
        $dtMinusDay = (clone $dt)->sub(new \DateInterval('P1D'));

        $generalQuoteInfo = new \ReninsApi\Request\Soap\Import\GeneralQuoteInfo();
        $generalQuoteInfo->SALE_DATE = $dt->format('Y-m-d') . 'T12:00:00';
        $generalQuoteInfo->CURRENCY = 'RUR';

        $employee = new \ReninsApi\Request\Soap\Import\Employee();
        $employee->LAST_NAME = 'Кротова';
        $employee->FIRST_NAME = 'Елизавета';
        $employee->LAST_NAME = 'Павловна';
        $employee->BIRTH_DATE = '1980-02-07';
        $employee->ROLE = 'сотрудник';

        $seller = new \ReninsApi\Request\Soap\Import\Seller();
        $seller->TYPE = 'EMPLOYEE';
        $seller->EMPLOYEE = $employee;
        $seller->MANAGERS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Manager(['name' => 'Иванова И.И.']),
        ]);

        $contact = new \ReninsApi\Request\Soap\Import\Contact();
        $contact->IPFLAG = false;
        $contact->HOME_PHONE = '+74957554321';
        $contact->CELL_PHONE = '+74955654321';
        $contact->LAST_NAME = 'Дмитриев';
        $contact->FIRST_NAME = 'Александр';
        $contact->MIDDLE_NAME = 'Андреевич';
        $contact->BIRTH_DATE = '1980-10-07';
        $contact->RESIDENT = true;
        $contact->CONTACT_ADDRESSES = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Address([
                'TYPE' => 'ADDR_CON_REG',
                'COUNTRY' => 'Российская Федерация',
                'CITY' => 'Москва',
                'STREET' => 'Льва Яшина ул.',
                'HOUSE' => '1',
                'APPARTMENT' => '123',
            ]),
        ]);
        $contact->CONTACT_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PASSPORT',
                'SERIES' => '3455',
                'NUMBER' => '657653',
                'ISSUED_DATE' => '1998-10-10',
                'ISSUED_WHERE' => 'РОВД г. Москвы',
            ])
        ]);

        $insurant = new \ReninsApi\Request\Soap\Import\ContactInfo();
        $insurant->TYPE = 'CONTACT';
        $insurant->CONTACT = $contact;

        $privateQuoteInfo = new \ReninsApi\Request\Soap\Import\PrivateQuoteInfo();
        $privateQuoteInfo->DOCUMENT_OF_PAYMENT = new \ReninsApi\Request\Soap\Import\DocumentOfPayment([
            'TYPE' => 'по квитанции СБЕРБАНКА',
            'PAY_DOC_NUMBER' => '0443457789',
            'PAY_DOC_ISSUE_DATE' => $dtMinusDay->format('Y-m-d'),
        ]);
        $privateQuoteInfo->BSO_NUMBER = '1234567'; //for test
        $privateQuoteInfo->CREATION_DATE = $dtMinusDay->format('Y-m-d');
        $privateQuoteInfo->INS_DATE_FROM = $this->getDateBegin()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_FROM = $this->getDateBegin()->format('H:i:s');
        $privateQuoteInfo->INS_DATE_TO = $this->getDateEnd()->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_TO = $this->getDateEnd()->format('H:i:s');
        //$privateQuoteInfo->INSURANCE_SUM = '400000'; from CalcResults
        $privateQuoteInfo->CURRENCY = 'RUR';
        $privateQuoteInfo->INS_DURATION = 12;
        $privateQuoteInfo->TOTALLY = false;
        $privateQuoteInfo->RISKS = new \ReninsApi\Request\Soap\Import\Risks([
            'RISK' => new ContainerCollection(),
        ]);

        $vehicle = new \ReninsApi\Request\Soap\Import\Vehicle();
        $vehicle->TYPE = 'Легковое ТС';
        $vehicle->BRAND = 'ВАЗ';
        $vehicle->MODEL = 'Granta';
        $vehicle->PRICE = '450000';
        $vehicle->POWER = '98';
        $vehicle->YEAR = date('Y');
        $vehicle->VIN = 'AB1CDE23FGH457789';
        $vehicle->REG_SIGN = 'У132ЕР163';
        $vehicle->COLOR = 'Металик';
        $vehicle->PURPOSE = 'личная';
        $vehicle->ENGINE_VOLUME = '1600';
        $vehicle->KEY_COUNT = 2;
        $vehicle->ENGINE_TYPE = 'Бензиновый';
        $vehicle->TRANSMISSION_TYPE = 'МКПП';
        $vehicle->VEHICLE_BODY_TYPE = 'Седан';
        $vehicle->VEHICLE_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PTS',
                'SERIES' => '1234',
                'NUMBER' => '345344',
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
            'MIN_EXPERIENCE' => 10,
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
        @file_put_contents(TEMP_DIR . '/CascoCalcResponse3.txt', $data);

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

        @file_put_contents(TEMP_DIR . '/CascoCalcResults3.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта

        @file_put_contents(TEMP_DIR . '/CascoAccountNumber3.txt', $calcResults->AccountNumber); //Номер котировки
    }

    /**
     * Печать результатов расчета
     */
    public function testPrePrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber3.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new \ReninsApi\Request\Soap\Printing\PrintingParams(['DocumentTypeId' => 1]),
        ]);

        $response = $client->printDocumentsToBinary($param);

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        /* @var DocBytesResponseEx $docBytesResponseEx */
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(true, $docBytesResponseEx->Success);
        $this->assertNotNull($docBytesResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/CascoCalcResults3-1.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }

    /**
     * Получить номер полиса для импорта
     */
    public function testGetPolicyNumber()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber3.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }

        $request = new \ReninsApi\Request\Soap\Import\Request();
        $request->AccountNumber = $accountNumber;
        $response = $client->GetPolicyNumber($request);

        //print_r($response);

        $this->assertEquals(true, $response->Success);

        @file_put_contents(TEMP_DIR . '/CascoPolicyNumber3.txt', $response->Number); //Номер, который нужен при импорте
    }

    /**
     * Импорт полиса
     */
    public function testImport()
    {
        $client = $this->createApi2();

        $calcResults = @file_get_contents(TEMP_DIR . '/CascoCalcResults3.txt');
        if (!$calcResults) {
            throw new \Exception("CalcResults aren't calculated.");
        }
        $calcResults = @unserialize($calcResults);
        if (!is_array($calcResults)) {
            throw new \Exception("CalcResults has invalid format");
        }

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber3.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }

        $policyNumber = @file_get_contents(TEMP_DIR . '/CascoPolicyNumber3.txt');
        if (!$policyNumber) {
            throw new \Exception("PolicyNumber isn't calculated.");
        }

        $request = $this->getImportRequest();
        $request->GENERAL_QUOTE_INFO->ACCOUNT_NUMBER_CALCBASED_ON = $accountNumber;
        $request->GENERAL_QUOTE_INFO->PACKET_CALCBASED_ON = $calcResults['Risks']['PacketName'];
        $request->GENERAL_QUOTE_INFO->INSURANCE_SUM = $calcResults['Total']['Sum'];

        /* @var \ReninsApi\Request\Soap\Import\Context $context */
        $context = $request->LIST_OF_CONTEXTS->get(0);
        $context->PRIVATE_QUOTE_INFO->POLICY_NUMBER = $policyNumber;
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
        //print_r($response);

        $this->assertGreaterThan(0, strlen($response->PolicyId));
        $this->assertGreaterThan(0, strlen($response->AccountNumber));
        $this->assertGreaterThan(0, strlen($response->PrintToken));
        $this->assertGreaterThan(0, $response->AvailableDocumentTypes->count());

        @file_put_contents(TEMP_DIR . '/CascoPrintToken3.txt', $response->PrintToken); //Номер, который нужен для окончательной печати полиса
    }

    /**
     * Печать полиса
     */
    public function testFinishPrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber3.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }
        $printToken = @file_get_contents(TEMP_DIR . '/CascoPrintToken3.txt');
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

        @file_put_contents(TEMP_DIR . '/CascoCalcResults3-7.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }





}
