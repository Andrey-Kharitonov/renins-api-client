<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * Doc bytes info for PrintDocumentsToBinary
 *
 * Поведение полностью идентично StorageKeyResponseEx, со всеми странностями
 *
 * @property boolean $Success
 * @property ContainerCollection $Messages
 * @property DocBytesResult $Result
 * @property ContainerCollection $Errors
 */
class DocBytesResponseEx extends Container
{
    protected $rules = [
        'Success' => ['toBoolean', 'required'],
        'Messages' => ['containerCollection:' . Message::class],
        'Result' => ['container:' . DocBytesResult::class],
        'Errors' => ['containerCollection:' . Error::class],
    ];

    public function fromObject($obj) {
        $this->fromObjectOnly($obj, ['Success']);

        if (!empty($obj->Messages) && !empty($obj->Messages->Message)) {
            $this->Messages = ContainerCollection::createFromObject($obj->Messages->Message, Message::class);
        }

        if (!empty($obj->Result)) {
            $this->Result = DocBytesResult::createFromObject($obj->Result);
        }

        if (!empty($obj->Errors) && !empty($obj->Errors->Error)) {
            $this->Errors = ContainerCollection::createFromObject($obj->Errors->Error, Error::class);
        }

        return $this;
    }
}
