<?php

namespace ReninsApiTest\Client;

use ReninsApi\Client\ApiVersion2;
use ReninsApi\Helpers\LogEvent;

trait Log
{
    public function onLog(LogEvent $event) {
        global $loggerCalc;
        $loggerCalc->info("{$event->method}: {$event->message}", $event->data);
    }

    /**
     * @return ApiVersion2
     */
    protected function createApi2() {
        $client = new ApiVersion2(CLIENT_SYSTEM_NAME, PARTNER_UID);
        $client->setWsdlCalc(WSDL_CALC)
            ->setWsdlImport(WSDL_IMPORT)
            ->setWsdlPrint(WSDL_PRINT)
            ->setUrlRest(URL_REST)
            ->onLog = [$this, 'onLog'];

        return $client;
    }
}
