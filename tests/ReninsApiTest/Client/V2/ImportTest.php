<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Import\Context;
use ReninsApi\Request\Soap\Import\InputMessage;
use ReninsApi\Request\Soap\Import\Request;
use ReninsApiTest\Client\Log;

class ImportTest extends TestCase
{
    use Log;

    /**
     * КАСКО, ФЛ, ЛДУ (импорт, запрос).xml
     * @return InputMessage
     */
    private function getRequest() {
        $dt = new \DateTime();
        $dtMinusDay = (clone $dt)->sub(new \DateInterval('P1D'));
        $dtPlusYear = (clone $dt)->add(new \DateInterval('P1Y'))->sub(new \DateInterval('P1D'));

        $generalQuoteInfo = new \ReninsApi\Request\Soap\Import\GeneralQuoteInfo();
        $generalQuoteInfo->SALE_DATE = $dt->format('Y-m-d') . 'T12:00:00';
        $generalQuoteInfo->INSURANCE_SUM = '400000';
        $generalQuoteInfo->CURRENCY = 'RUR';
        $generalQuoteInfo->PACKET_CALCBASED_ON = '1';

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
        $contact->LAST_NAME = 'Иванов';
        $contact->FIRST_NAME = 'Иван';
        $contact->MIDDLE_NAME = 'Иванович';
        $contact->BIRTH_DATE = '1980-08-01';
        $contact->RESIDENT = true;
        $contact->CONTACT_ADDRESSES = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Address([
                'TYPE' => 'ADDR_CON_REG',
                'COUNTRY' => 'Российская Федерация',
                'CITY' => 'Тольятти',
                'STREET' => 'Автостроителей',
                'HOUSE' => '11',
                'APPARTMENT' => '1',
            ]),
        ]);
        $contact->CONTACT_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PASSPORT',
                'SERIES' => '0000',
                'NUMBER' => '123456',
                'ISSUED_DATE' => '2000-01-01',
                'ISSUED_WHERE' => 'РОВД',
            ]),
        ]);

        $insurant = new \ReninsApi\Request\Soap\Import\ContactInfo();
        $insurant->TYPE = 'CONTACT';
        $insurant->CONTACT = $contact;

        $privateQuoteInfo = new \ReninsApi\Request\Soap\Import\PrivateQuoteInfo();
        $privateQuoteInfo->DOCUMENT_OF_PAYMENT = new \ReninsApi\Request\Soap\Import\DocumentOfPayment([
            'TYPE' => 'по квитанции СБЕРБАНКА',
            'PAY_DOC_NUMBER' => '0123456789',
            'PAY_DOC_ISSUE_DATE' => $dtMinusDay->format('Y-m-d'),
        ]);
        $privateQuoteInfo->POLICY_NUMBER = ''; //get by GetPolicyNumber()
        //$privateQuoteInfo->BSO_NUMBER = '0000000';
        //$PRIVATE_QUOTE_INFO->PRODUCT = 'КАСКО 2015.11.15'; deprecated
        $privateQuoteInfo->CREATION_DATE = $dtMinusDay->format('Y-m-d');
        $privateQuoteInfo->INS_DATE_FROM = $dt->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_FROM = '12:00:00';
        $privateQuoteInfo->INS_DATE_TO = $dtPlusYear->format('Y-m-d');
        $privateQuoteInfo->INS_TIME_TO = '23:59:00';
        $privateQuoteInfo->INSURANCE_SUM = '400000';
        $privateQuoteInfo->CURRENCY = 'RUR';
        $privateQuoteInfo->INS_DURATION = 12;
        $privateQuoteInfo->TOTALLY = false;
        $privateQuoteInfo->PRE_INSURANCE_INSPECTION = new \ReninsApi\Request\Soap\Import\PreInsuranceInspection([
            'NEW_OBJECT' => false,
            'INSPECTION_IS_NEEDED' => true,
            'INSPECTION_NOT_NEEDED_OLD_OBJECT' => false,
        ]);
        $privateQuoteInfo->RISKS = new \ReninsApi\Request\Soap\Import\Risks([
            'BONUS' => '41553',
            'RISK' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'Угон', 'BONUS' => '3387', 'INSURANCE_SUM' => '400000']),
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'ДО', 'BONUS' => '15000', 'INSURANCE_SUM' => '100000']),
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'ДР', 'BONUS' => '185', 'INSURANCE_SUM' => '10000']),
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'НС', 'BONUS' => '350', 'INSURANCE_SUM' => '100000']),
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'Ущерб', 'BONUS' => '22631', 'INSURANCE_SUM' => '400000']),
            ]),
        ]);

        $vehicle = new \ReninsApi\Request\Soap\Import\Vehicle();
        $vehicle->TYPE = 'Легковое ТС';
        $vehicle->BRAND = 'ВАЗ';
        $vehicle->MODEL = '1117 Kalina';
        $vehicle->PRICE = '400000';
        $vehicle->POWER = '98';
        $vehicle->YEAR = date('Y');
        $vehicle->VIN = 'AB1CDE23FGH456789';
        $vehicle->REG_SIGN = 'У123ЕО12';
        $vehicle->COLOR = 'Серебристый';
        $vehicle->IS_LEASE = false;
        $vehicle->IS_CREDIT = false;
        $vehicle->PURPOSE = 'личная';
        $vehicle->KEY_COUNT = 2;
        $vehicle->ENGINE_VOLUME = '1598';
        $vehicle->ENGINE_TYPE = 'Бензиновый';
        $vehicle->TRANSMISSION_TYPE = 'МКПП';
        $vehicle->VEHICLE_BODY_TYPE = 'Седан';
        $vehicle->VEHICLE_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'PTS',
                'SERIES' => '40НТ',
                'NUMBER' => '123456',
            ]),
        ]);
        $vehicle->EXTRAS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Equipment([
                'MARK' => 'Марка',
                'MODEL' => 'Модель',
                'AMOUNT' => 1,
                'COST' => 100000,
            ]),
        ]);

        $owner = new \ReninsApi\Request\Soap\Import\Owner();
        $owner->TYPE = 'CONTACT';
        $owner->CONTACT = $contact;

        $contact2 = new \ReninsApi\Request\Soap\Import\Contact();
        $contact2->LAST_NAME = 'Иванов';
        $contact2->FIRST_NAME = 'Иван';
        $contact2->MIDDLE_NAME = 'Иванович';
        $contact2->BIRTH_DATE = '1980-08-01';
        $contact2->DRIVE_EXPERIENCE = '2001-05-05';
        $contact2->CONTACT_DOCUMENTS = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Document([
                'TYPE' => 'DRIVING_LICENCE',
                'SERIES' => '12ВА',
                'NUMBER' => '123456',
                'ISSUED_DATE' => '2001-01-01',
            ]),
        ]);

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
            'MIN_AGE' => 31,
            'MIN_EXPERIENCE' => 5,
            'MULTI_DRIVE' => true,
            'STAFF' => false,

            /*
            'DRIVER' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Driver([
                    'CONTACT' => $contact2,
                ]),
            ]),
            */
        ]);


        $inputMessage = new InputMessage();
        $inputMessage->GENERAL_QUOTE_INFO = $generalQuoteInfo;
        $inputMessage->SELLER = $seller;
        $inputMessage->INSURANT = $insurant;
        $inputMessage->LIST_OF_CONTEXTS = new ContainerCollection([
            $context
        ]);

        return $inputMessage;
    }

    /**
     * @group import
     * @group casco
     */
    public function testGetPolicyNumber()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber1.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated. Run calculation tests before.");
        }

        $request = new Request();
        $request->AccountNumber = $accountNumber;
        $response = $client->GetPolicyNumber($request);

        print_r($response);

        $this->assertObjectHasAttribute('Success', $response);
        $this->assertObjectHasAttribute('Number', $response);
        $this->assertEquals(true, $response->Success);
        $this->assertGreaterThan(0, strlen($response->Number));

        @file_put_contents(TEMP_DIR . '/CascoPolicyNumber1.txt', $response->Number); //Номер, который нужен при импорте
    }

    /**
     * @group import
     * @group casco
     */
    public function testCasco()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber1.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated. Run calculation tests before.");
        }
        $policyNumber = @file_get_contents(TEMP_DIR . '/CascoPolicyNumber1.txt');
        if (!$policyNumber) {
            throw new \Exception("PolicyNumber isn't calculated. Run testGetPolicyNumber() before.");
        }

        $request = $this->getRequest();
        $request->GENERAL_QUOTE_INFO->ACCOUNT_NUMBER_CALCBASED_ON = $accountNumber;

        /* @var Context $context */
        $context = $request->LIST_OF_CONTEXTS->get(0);
        $context->PRIVATE_QUOTE_INFO->POLICY_NUMBER = $policyNumber;

        $response = $client->ImportPolicy($request);
        print_r($response);

        $this->assertObjectHasAttribute('ErrorCode', $response);
        $this->assertObjectHasAttribute('PolicyId', $response);
        $this->assertObjectHasAttribute('AccountNumber', $response);
        $this->assertObjectHasAttribute('AvailableDocumentTypes', $response);
        $this->assertObjectHasAttribute('PolicyDocumentType', $response->AvailableDocumentTypes);
        $this->assertObjectHasAttribute('PrintToken', $response);
        $this->assertEquals(0, $response->ErrorCode);
        $this->assertGreaterThan(0, strlen($response->AccountNumber));
        $this->assertGreaterThan(0, count($response->AvailableDocumentTypes->PolicyDocumentType));
        $this->assertGreaterThan(0, strlen($response->PrintToken));

        @file_put_contents(TEMP_DIR . '/CascoPrintToken1.txt', $response->PrintToken); //Номер, который нужен для окончательной печати полиса
    }

}
