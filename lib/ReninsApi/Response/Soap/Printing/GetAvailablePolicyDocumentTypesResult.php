<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Soap\Error;
use ReninsApi\Response\Soap\PolicyDocumentType;

/**
 * GetAvailablePolicyDocumentTypes results
 *
 * @property boolean $Success
 * @property ContainerCollection $PolicyDocumentTypes
 * @property ContainerCollection $Errors
 */
class GetAvailablePolicyDocumentTypesResult extends Container
{
    protected $rules = [
        'Success' => ['toBoolean', 'required'],
        'PolicyDocumentTypes' => ['containerCollection:' . PolicyDocumentType::class],
        'Errors' => ['containerCollection:' . Error::class],
    ];

    public function fromObject($obj) {
        $this->fromObjectOnly($obj, ['Success']);

        if (!empty($obj->PolicyDocumentTypes) && !empty($obj->PolicyDocumentTypes->PolicyDocumentType)) {
            $this->PolicyDocumentTypes = ContainerCollection::createFromObject($obj->PolicyDocumentTypes->PolicyDocumentType, PolicyDocumentType::class);
        }

        if (!empty($obj->Errors) && !empty($obj->Errors->Error)) {
            $this->Errors = ContainerCollection::createFromObject($obj->Errors->Error, Error::class);
        }

        return $this;
    }
}