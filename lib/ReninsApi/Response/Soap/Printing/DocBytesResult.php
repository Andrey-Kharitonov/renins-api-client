<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * Doc bytes result
 *
 * @property integer $PdfPageCount
 * @property string $PrintResultType
 * @property string $DocBytes
 */
class DocBytesResult extends Container
{
    protected $rules = [
        'PdfPageCount' => ['toInteger'],
        'PrintResultType' => ['toString'],
        'DocBytes' => ['toString', 'required', 'notEmpty'],
    ];
}