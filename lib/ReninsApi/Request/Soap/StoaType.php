<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;

/**
 * Stoa type
 *
 * @property int $type
 * @property string|bool $enabled
 */
class StoaType extends Container
{
    protected static $rules = [
        'type' => ['toInteger', 'required', 'notEmpty'], //1-8
        'enabled' => ['toLogical', 'required'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $xml->addAttribute('type', $this->type);
        $xml[0] = $this->enabled;

        return $this;
    }

    protected $type;
    protected $enabled;
}