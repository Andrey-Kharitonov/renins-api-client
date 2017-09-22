<?php

namespace ReninsApi\Request\Soap\Calc;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Container;

/**
 * Some calculation
 *
 * @property int $type - default 0
 * @property string $uid
 * @property Policy $Policy
 */
class CalculationCasco extends Container
{
    protected $rules = [
        'type' => ['toInteger', 'required', 'in:0|1'],
        'uid' => ['toString', 'required', 'notEmpty'],
        'Policy' => ['container:' . Policy::class, 'required'],
    ];

    protected function init()
    {
        $this->set('type', 0);
    }

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['type', 'uid']);
        $this->toXmlTags($xml, ['Policy']);

        return $this;
    }

    /**
     * Generate unique uid
     * @return $this
     */
    public function genUuid() {
        $this->uid = Utils::genUuid();
        return $this;
    }
}