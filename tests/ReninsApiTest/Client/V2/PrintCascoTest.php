<?php

namespace ReninsApiTest\Client\V2;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Printing\PrintingParams;
use ReninsApi\Request\Soap\Printing\Request;
use ReninsApi\Response\Soap\Printing\DocBytesResponseEx;
use ReninsApi\Response\Soap\Printing\StorageKeyResponseEx;
use ReninsApiTest\Client\Log;

class PrintTest extends TestCase
{
    use Log;

    /**
     * @group printing-casco
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
        //print_r($response->toArray());
        $this->assertEquals(true, $response->Success);
        $this->assertGreaterThan(0, $response->PolicyDocumentTypes->count());
    }

    /**
     * @group printing-casco
     */
    public function testGetAvailablePolicyDocumentTypesFail()
    {
        $client = $this->createApi2();

        $param = new Request();
        $param->AccountNumber = 'Error here';
        $param->isPrintAsOneDocument = true;

        $response = $client->getAvailablePolicyDocumentTypes($param);
        //print_r($response->toArray());
        $this->assertEquals(false, $response->Success);
        $this->assertGreaterThan(0, $response->Errors->count());
    }

    /**
     * @group printing-casco
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
        //print_r($response->toArray());

        $this->assertGreaterThan(0, $response->StorageKeyResponseEx->count());
        /* @var StorageKeyResponseEx $storageKeyResponseEx */
        $storageKeyResponseEx = $response->StorageKeyResponseEx->get(0);
        $this->assertEquals(true, $storageKeyResponseEx->Success);
        $this->assertNotNull($storageKeyResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/CascoTemporaryKey1.txt', $storageKeyResponseEx->Result->TemporaryKey); //Результаты расчета, временный ключ
    }

    /**
     * @group printing-casco
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
        //print_r($response->toArray());

        $this->assertGreaterThan(0, $response->StorageKeyResponseEx->count());
        /* @var StorageKeyResponseEx $storageKeyResponseEx */
        $storageKeyResponseEx = $response->StorageKeyResponseEx->get(0);
        $this->assertEquals(false, $storageKeyResponseEx->Success);
    }

    /**
     * @group printing-casco
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
            new PrintingParams(['DocumentTypeId' => 1]),
        ]);

        $response = $client->printDocumentsToBinary($param);

        /*
        ob_start();
        var_dump($response->toArray());
        $data = ob_get_clean();
        @file_put_contents(TEMP_DIR . '/var_dump.txt', $data);
        */

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        /* @var DocBytesResponseEx $docBytesResponseEx */
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(true, $docBytesResponseEx->Success);
        $this->assertNotNull($docBytesResponseEx->Result);

        @file_put_contents(TEMP_DIR . '/CascoCalcResults1.pdf', $docBytesResponseEx->Result->DocBytes); //печатная форма "Результаты расчета", pdf
    }

    /**
     * @group printing-casco
     */
    public function testPrintDocumentsToBinaryFail()
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
            new PrintingParams(['DocumentTypeId' => 2]), //Error Here
        ]);

        $response = $client->printDocumentsToBinary($param);

        $this->assertGreaterThan(0, $response->DocBytesResponseEx->count());
        /* @var DocBytesResponseEx $docBytesResponseEx */
        $docBytesResponseEx = $response->DocBytesResponseEx->get(0);
        $this->assertEquals(false, $docBytesResponseEx->Success);
    }

    /**
     * @group printing-casco
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
     * @group printing-casco
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

