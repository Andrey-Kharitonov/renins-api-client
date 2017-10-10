<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * Storage key result
 *
 * @property integer $PdfPageCount
 * @property string $PrintResultType
 * @property string $TemporaryKey
 */
class StorageKeyResult extends Container
{
    protected $rules = [
        'PdfPageCount' => ['toInteger'],
        'PrintResultType' => ['toString'],
        'TemporaryKey' => ['toString', 'required', 'notEmpty'],
        //ExtensionData
    ];
}