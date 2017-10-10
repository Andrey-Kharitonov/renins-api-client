<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Request for GetAvailablePolicyDocumentTypes
 *
 * @property PrintRequest $request
 */
class GetAvailablePolicyDocumentTypes extends Container
{
    protected $rules = [
        'request' => ['container:' . PrintRequest::class, 'required'],
    ];
}