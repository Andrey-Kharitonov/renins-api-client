<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;

/**
 * ФИО менеджера, оформлявшего договор
 *
 * @property string $name
 */
class MANAGER extends Container
{
    protected $rules = [
        'name' => ['toString', 'required', 'notEmpty'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $xml[0] = $this->name;
        return $this;
    }
}