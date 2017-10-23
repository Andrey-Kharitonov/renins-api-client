<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApiTest\Client\Log;

class ProlongationCascoTest extends TestCase
{
    use Log;

    /**
     * @group prolongation-casco
     */
    public function testCalc()
    {
        $client = $this->createApi2();

        $Vehicle = new \ReninsApi\Request\Soap\Calculation\Vehicle();
        $Vehicle->Type = 'Легковое ТС';

        $Policy = new \ReninsApi\Request\Soap\Calculation\Policy();
        $Policy->Casco = new \ReninsApi\Request\Soap\Calculation\Casco([
            'Stoa' => new ContainerCollection([
                new \ReninsApi\Request\Soap\Calculation\StoaType([
                    'type' => 3,
                    'enabled' => false,
                ]),
                new \ReninsApi\Request\Soap\Calculation\StoaType([
                    'type' => 6,
                    'enabled' => true,
                ])
            ])
        ]);
        $Policy->ContractTerm = new \ReninsApi\Request\Soap\Calculation\ContractTerm([
            'Product' => 1,
        ]);
        $Policy->Vehicle = $Vehicle;

        $request = new \ReninsApi\Request\Soap\Calculation\Request();
        $request->type = 1;
        $request->genUuid();
        $request->Policy = $Policy;
        $request->Prolongation = new \ReninsApi\Request\Soap\Calculation\Prolongation([
            'prolongationNumber' => '001AT-17/36537-S',
            'insurantName' => 'Иванова',
            'AutomaticProlongation' => true,
        ]);


        $response = $client->calc($request);
        print_r($response->toArray());

        ob_start();
        print_r($response->toArray());
        $data = ob_get_clean();
        @file_put_contents(TEMP_DIR . '/CascoCalcResponse5.txt', $data);

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

        @file_put_contents(TEMP_DIR . '/CascoCalcResults5.txt', serialize($calcResults->toArray())); //Результаты расчета для импорта

        @file_put_contents(TEMP_DIR . '/CascoAccountNumber5.txt', $calcResults->AccountNumber); //Номер котировки

        @file_put_contents(TEMP_DIR . '/CascoPrintToken5.txt', $response->printToken); //Токен печати
    }

    /**
     * Получить номер полиса для импорта
     * @group prolongation-casco
     */
    public function testGetPolicyNumber()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber5.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }

        $request = new \ReninsApi\Request\Soap\Import\Request();
        $request->AccountNumber = $accountNumber;
        $response = $client->GetPolicyNumber($request);

        //print_r($response);

        $this->assertEquals(true, $response->Success);

        @file_put_contents(TEMP_DIR . '/CascoPolicyNumber5.txt', $response->Number); //Номер, который нужен при импорте
    }

    /**
     * @group prolongation-casco
     */
    public function testGetAvailablePolicyDocumentTypes()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber5.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated. Run calculation tests before.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;

        $response = $client->getAvailablePolicyDocumentTypes($param);
        //print_r($response->toArray());
        $this->assertEquals(true, $response->Success);
        $this->assertGreaterThan(0, $response->PolicyDocumentTypes->count());
    }

    /**
     * Печать результатов расчета
     * @group prolongation-casco
     * @group current
     */
    public function testPrePrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber5.txt');
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

        @file_put_contents(TEMP_DIR . '/CascoCalcResults5-1.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }

    /**
     * Печать полиса
     * @group prolongation-casco
     */
    public function testFinishPrint()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber5.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated.");
        }
        $printToken = @file_get_contents(TEMP_DIR . '/CascoPrintToken5.txt');
        if (!$printToken) {
            throw new \Exception("PrintToken isn't calculated.");
        }

        $param = new \ReninsApi\Request\Soap\Printing\Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new \ReninsApi\Request\Soap\Printing\PrintingParams([
                'DocumentTypeId' => 7,
                'DocumentLabels' => ['NewCasco', 'HasStamp', 'HaveStamp'],
            ]),
        ]);
        $param->PrintToken = $printToken;

        $response = $client->printDocumentsToBinary($param);

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        /* @var DocBytesResponseEx $docBytesResponseEx */
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(true, $docBytesResponseEx->Success);
        $this->assertNotNull($docBytesResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/CascoCalcResults2-7.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }
}
