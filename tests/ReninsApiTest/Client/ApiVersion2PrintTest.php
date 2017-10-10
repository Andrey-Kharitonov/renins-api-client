<?php

namespace ReninsApiTest\Client;

use PHPUnit\Framework\TestCase;
use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\GetAvailablePolicyDocumentTypes;
use ReninsApi\Request\Soap\PrintingParams;
use ReninsApi\Request\Soap\PrintRequest;

class ApiVersion2PrintTest extends TestCase
{
    public function onLog(LogEvent $event) {
        global $loggerCalc;
        $loggerCalc->info("{$event->method}: {$event->message}", $event->data);
    }

    /**
     * @group soap
     * @group current
     */
    public function testCalcCascoSuccessful()
    {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->onLog = [$this, 'onLog'];

        $printingParamsItems = new ContainerCollection([
            new PrintingParams(['DocumentTypeId' => 1, 'DocumentLabels' => ['NoHasStamp']]),
            new PrintingParams(['DocumentTypeId' => 2, 'DocumentLabels' => ['NoHasStamp']]),
        ]);

        $param = new GetAvailablePolicyDocumentTypes();
        $param->request = new PrintRequest();
        $param->request->AccountNumber = 1;
        $param->request->PrintToken = 2;
        $param->request->isPrintAsOneDocument = true;
        $param->request->printingParamsItems = $printingParamsItems;

        $response = $client->getAvailablePolicyDocumentTypes($param);
    }

}
