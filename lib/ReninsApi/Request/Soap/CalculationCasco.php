<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Container;

/**
 * Some calculation
 *
 * @property int $type
 * @property string $uid
 * @property Policy $Policy
 */
class CalculationCasco extends Container
{
    protected static $rules = [
        'type' => ['toInteger', 'required', 'in:0|1'],
        'uid' => ['toString', 'required', 'notEmpty'],
        'Policy' => ['container', 'required'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $this->toXmlAttributes($xml, ['type', 'uid']);
        $this->toXmlTags($xml, ['Policy']);

        return $this;
    }

    protected $type = 0;
    protected $uid;
    protected $Policy;

    /**
     * Generate unique uid
     * @return $this
     */
    public function genUuid() {
        $this->uid = Utils::genUuid();
        return $this;
    }
}