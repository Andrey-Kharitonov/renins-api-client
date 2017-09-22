<?php

namespace ReninsApi\Request\Soap\Printing;

use ReninsApi\Request\Container;

/**
 * Request for GetAvailablePolicyDocumentTypes
 *
 * @property Request $request
 */
class GetAvailablePolicyDocumentTypes extends Container
{
    protected $rules = [
        'request' => ['container:' . Request::class, 'required'],
    ];
}