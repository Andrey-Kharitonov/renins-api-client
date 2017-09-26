<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Printing\PrintingParams;
use ReninsApi\Request\Soap\Printing\Request;
use ReninsApiTest\Client\Log;

class PrintTest extends TestCase
{
    use Log;

    /**
     * @group printing
     * @group casco
     */
    public function testGetAvailablePolicyDocumentTypes()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber1.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated. Run calculation tests before.");
        }

        $param = new Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;

        $response = $client->getAvailablePolicyDocumentTypes($param);
        print_r($response);
        $this->assertObjectHasAttribute('Success', $response);
        $this->assertObjectHasAttribute('PolicyDocumentTypes', $response);
        $this->assertObjectHasAttribute('Errors', $response);
        $this->assertEquals(true, $response->Success);
        $this->assertObjectHasAttribute('PolicyDocumentType', $response->PolicyDocumentTypes);
        $this->assertGreaterThan(0, count($response->PolicyDocumentTypes->PolicyDocumentType));
    }

    /**
     * @group printing
     * @group casco
     */
    public function testGetAvailablePolicyDocumentTypesFail()
    {
        $client = $this->createApi2();

        $param = new Request();
        $param->AccountNumber = 'Error here';
        $param->isPrintAsOneDocument = true;

        $response = $client->getAvailablePolicyDocumentTypes($param);
        $this->assertObjectHasAttribute('Success', $response);
        $this->assertObjectHasAttribute('Errors', $response);
        $this->assertEquals(false, $response->Success);
        $this->assertObjectHasAttribute('Error', $response->Errors);
    }

    /**
     * @group printing
     * @group casco
     */
    public function testPrintDocuments()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber1.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated. Run calculation tests before.");
        }

        $param = new Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new PrintingParams(['DocumentTypeId' => 1]),
        ]);

        $response = $client->printDocuments($param);
        $this->assertObjectHasAttribute('Success', $response);
        $this->assertObjectHasAttribute('Result', $response);
        $this->assertObjectHasAttribute('Errors', $response);
        $this->assertEquals(true, $response->Success);
        $this->assertObjectHasAttribute('PdfPageCount', $response->Result);
        $this->assertObjectHasAttribute('PrintResultType', $response->Result);
        $this->assertObjectHasAttribute('TemporaryKey', $response->Result);

        @file_put_contents(TEMP_DIR . '/CascoTemporaryKey1.txt', $response->Result->TemporaryKey); //Результаты расчета, временный ключ
    }

    /**
     * @group printing
     * @group casco
     */
    public function testPrintDocumentsFail()
    {
        $client = $this->createApi2();

        $param = new Request();
        $param->AccountNumber = 'Error here';
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new PrintingParams(['DocumentTypeId' => 1]),
        ]);

        $response = $client->printDocuments($param);
        $this->assertObjectHasAttribute('Success', $response);
        $this->assertObjectHasAttribute('Errors', $response);
        $this->assertEquals(false, $response->Success);
        $this->assertObjectHasAttribute('Error', $response->Errors);
    }

    /**
     * @group printing
     * @group casco
     * @group current
     */
    public function testPrintDocumentsToBinary()
    {
        $client = $this->createApi2();

        $accountNumber = @file_get_contents(TEMP_DIR . '/CascoAccountNumber1.txt');
        if (!$accountNumber) {
            throw new \Exception("AccountNumber isn't calculated. Run calculation tests before.");
        }

        $param = new Request();
        $param->AccountNumber = $accountNumber;
        $param->isPrintAsOneDocument = true;
        $param->printingParamsItems = new ContainerCollection([
            new PrintingParams(['DocumentTypeId' => 8]),
            new PrintingParams(['DocumentTypeId' => 10]),
        ]);

        $response = $client->printDocumentsToBinary($param);

        /*
        ob_start();
        var_dump($response);
        $data = ob_get_clean();
        @file_put_contents(TEMP_DIR . '/var_dump.txt', $data);
        */

        $this->assertObjectHasAttribute('Success', $response);
        $this->assertObjectHasAttribute('Result', $response);
        $this->assertObjectHasAttribute('Errors', $response);
        $this->assertEquals(true, $response->Success);
        $this->assertObjectHasAttribute('DocBytes', $response->Result);

        @file_put_contents(TEMP_DIR . '/CascoCalcResults1.pdf', $response->Result->DocBytes); //Результаты расчета, pdf
    }

    /**
     * @group printing
     * @group casco
     */
    public function testGetDocumentInUrl()
    {
        $client = $this->createApi2();

        $temporaryKey = @file_get_contents(TEMP_DIR . '/CascoTemporaryKey1.txt');
        if (!$temporaryKey) {
            throw new \Exception("TemporaryKey isn't calculated");
        }

        $response = $client->getDocumentInUrl($temporaryKey);
        $this->assertGreaterThan(0, strlen(strstr($response, 'http')));
    }

    /**
     * @group printing
     * @group casco
     */
    public function testGetDocumentToBytes()
    {
        $client = $this->createApi2();

        $temporaryKey = @file_get_contents(TEMP_DIR . '/CascoTemporaryKey1.txt');
        if (!$temporaryKey) {
            throw new \Exception("TemporaryKey isn't calculated");
        }

        $response = $client->getDocumentToBytes($temporaryKey);
        $this->assertGreaterThan(0, strlen($response));
    }


}

