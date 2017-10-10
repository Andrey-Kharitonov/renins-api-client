<?php

namespace ReninsApi;

use ReninsApi\Client\ApiVersion2;

/**
 * Factory for api client
 */
class Factory
{
    const V2 = 'v2';

    /**
     * Make an api client. You can also create an api clients directly.
     * @param string $clientSystemName
     * @param string $partnerUid
     * @param string $version - api version
     * @return ApiVersion2
     */
    public static function makeApiClient(string $clientSystemName, string $partnerUid, string $version = self::V2)
    {
        switch ($version) {
            case self::V2:
                return new ApiVersion2($clientSystemName, $partnerUid);
                break;
            default:
                throw new \InvalidArgumentException("Version not supported");
        }
    }
}
