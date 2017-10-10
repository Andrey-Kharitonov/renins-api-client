<?php

namespace ReninsApiTest\Client;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Import\InputMessage;

class ApiVersion2ImportTest extends TestCase
{
    public function onLog(LogEvent $event) {
        global $loggerCalc;
        $loggerCalc->info("{$event->method}: {$event->message}", $event->data);
    }

    /**
     * КАСКО, ФЛ, ЛДУ (импорт, запрос).xml
     * @return InputMessage
     */
    private function getRequest() {
        $generalQuoteInfo = new \ReninsApi\Request\Soap\Import\GeneralQuoteInfo();
        $generalQuoteInfo->ACCOUNT_NUMBER_CALCBASED_ON = 'AAA-101015-000';
        $generalQuoteInfo->SALE_DATE = '2015-11-11T11:42:58';
        $generalQuoteInfo->INSURANCE_SUM = '1280000';
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
        $contact->LAST_NAME = 'Иванов';
        $contact->FIRST_NAME = 'Иван';
        $contact->MIDDLE_NAME = 'Иванович';
        $contact->BIRTH_DATE = '1980-08-01';
        $contact->RESIDENT = true;
        $contact->CONTACT_ADDRESSES = new ContainerCollection([
            new \ReninsApi\Request\Soap\Import\Address([
                'TYPE' => 'ADDR_CON_REG',
                'COUNTRY' => 'Российская Федерация',
                'CITY' => 'нас.пункт',
                'STREET' => 'улица',
                'HOUSE' => 'номер дома',
                'APPARTMENT' => 'номер квартиры',
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

        $insurant = new \ReninsApi\Request\Soap\Import\Insurant();
        $insurant->TYPE = 'CONTACT';
        $insurant->CONTACT = $contact;

        $privateQuoteInfo = new \ReninsApi\Request\Soap\Import\PrivateQuoteInfo();
        $privateQuoteInfo->DOCUMENT_OF_PAYMENT = new \ReninsApi\Request\Soap\Import\DocumentOfPayment([
            'TYPE' => 'по квитанции СБЕРБАНКА',
            'PAY_DOC_NUMBER' => '0123456789',
            'PAY_DOC_ISSUE_DATE' => '2013-11-11',
        ]);
        $privateQuoteInfo->POLICY_NUMBER = '001AT-13/67757';
        $privateQuoteInfo->BSO_NUMBER = '3350773';
        //$PRIVATE_QUOTE_INFO->PRODUCT = 'КАСКО 2015.11.15'; deprecated
        $privateQuoteInfo->CREATION_DATE = '2013-11-11';
        $privateQuoteInfo->INS_DATE_FROM = '2013-11-11';
        $privateQuoteInfo->INS_TIME_FROM = '12:00:00';
        $privateQuoteInfo->INS_DATE_TO = '2014-09-27';
        $privateQuoteInfo->INS_TIME_TO = '23:59:00';
        $privateQuoteInfo->INSURANCE_SUM = '1280000';
        $privateQuoteInfo->CURRENCY = 'RUR';
        $privateQuoteInfo->INS_DURATION = 12;
        $privateQuoteInfo->TOTALLY = false;
        $privateQuoteInfo->PRE_INSURANCE_INSPECTION = new \ReninsApi\Request\Soap\Import\PreInsuranceInspection([
            'NEW_OBJECT' => false,
            'INSPECTION_IS_NEEDED' => true,
            'INSPECTION_NOT_NEEDED_OLD_OBJECT' => false,
        ]);
        $privateQuoteInfo->RISKS = new \ReninsApi\Request\Soap\Import\Risks([
            'BONUS' => '154704',
            'RISK' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'Угон', 'BONUS' => '13196', 'INSURANCE_SUM' => '1280000']),
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'Ущерб', 'BONUS' => '141308', 'INSURANCE_SUM' => '1280000']),
                new \ReninsApi\Request\Soap\Import\Risk(['NAME' => 'ДР', 'BONUS' => '200', 'INSURANCE_SUM' => '10000']),
            ]),
        ]);

        $vehicle = new \ReninsApi\Request\Soap\Import\Vehicle();
        $vehicle->TYPE = 'Легковое ТС';
        $vehicle->BRAND = 'Mazda';
        $vehicle->MODEL = '6';
        $vehicle->PRICE = '1280000';
        $vehicle->POWER = '150';
        $vehicle->YEAR = '2013';
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
                'COST' => 1000,
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
            'MIN_AGE' => 28,
            'MIN_EXPERIENCE' => 10,
            'MULTI_DRIVE' => false,
            'STAFF' => false,

            'DRIVER' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Import\Driver([
                    'CONTACT' => $contact2,
                ]),
            ]),
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
     * @group soap
     * @group current
     */
    public function testCasco()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $request = $this->getRequest();

        $response = $client->ImportPolicy($request);
        print_r($response);
        //$this->assertInstanceOf(\ReninsApi\Response\Soap\MakeCalculationResult::class, $response);
        //$this->assertEquals($response->isSuccessful(), true);
    }

}
