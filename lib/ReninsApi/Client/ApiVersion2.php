<?php

namespace ReninsApi\Client;

/**
 * API client v2
 */
class ApiVersion2 extends BaseApi
{
    use Methods\V2\VehicleBrands;
    use Methods\V2\Calculation;

    protected static $wsdl = '';
    protected static $wsdlTest = '';

    protected static $urlRest = '';
    protected static $urlRestTest = '';

}
