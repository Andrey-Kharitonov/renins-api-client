<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * PrintDocuments results
 *
 * По одному StorageKeyResponseEx на каждый тип документа, указанный в request.
 * Если указан isPrintAsOneDocument = true, то будет один StorageKeyResponseEx
 *
 * @property ContainerCollection $StorageKeyResponseEx
 */
class PrintDocumentsResult extends Container
{
    protected $rules = [
        'StorageKeyResponseEx' => ['containerCollection:' . StorageKeyResponseEx::class, 'required', 'length:1'],
    ];

    public function fromObject($obj) {
        if (!empty($obj->StorageKeyResponseEx)) {
            $this->StorageKeyResponseEx = ContainerCollection::createFromObject($obj->StorageKeyResponseEx, StorageKeyResponseEx::class);
        }

        return $this;
    }
}