<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * PrintDocuments results
 *
 * По одному DocBytesResponseEx на каждый тип документа, указанный в request.
 * Если указан isPrintAsOneDocument = true, то будет один DocBytesResponseEx
 *
 * @property ContainerCollection $DocBytesResponseEx
 */
class PrintDocumentsToBinaryResult extends Container
{
    protected $rules = [
        'DocBytesResponseEx' => ['containerCollection:' . DocBytesResponseEx::class, 'required', 'length:1'],
    ];

    public function fromObject($obj) {
        if (!empty($obj->DocBytesResponseEx)) {
            $this->DocBytesResponseEx = ContainerCollection::createFromObject($obj->DocBytesResponseEx, DocBytesResponseEx::class);
        }

        return $this;
    }
}