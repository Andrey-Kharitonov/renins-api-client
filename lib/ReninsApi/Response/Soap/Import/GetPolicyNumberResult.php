<?php
namespace ReninsApi\Response\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;
use ReninsApi\Response\Soap\Message;

/**
 * GetPolicyNumber results
 *
 * @property boolean $Success
 * @property string $Number - Номер полиса. Будет не пустым, если Success == true.
 * @property ContainerCollection $Messages - Сообщения. Будут не пустыми если Success == false
 */
class GetPolicyNumberResult extends Container
{
    protected $rules = [
        'Success' => ['toBoolean', 'required'],
        'Number' => ['toString'],
        'Messages' => ['containerCollection:' . Message::class],
    ];

    public function validate()
    {
        $errors = parent::validate();

        if ($this->Success) {
            if (!$this->Number) {
                $errors['Number'][] = "Is required for Success == true";
            }
        } else {
            if (!$this->Messages || !$this->Messages->count()) {
                $errors['Messages'][] = "Is required for Success == false";
            }
        }

        return $errors;
    }

    public function fromObject($obj) {
        $this->fromObjectOnly($obj, ['Success', 'Number']);

        if (!empty($obj->Messages) && !empty($obj->Messages->Message)) {
            $this->Messages = ContainerCollection::createFromObject($obj->Messages->Message, Message::class);
        }

        return $this;
    }
}