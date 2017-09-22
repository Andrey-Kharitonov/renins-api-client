<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Риски/покрытия.
 *
 * @property double $BONUS - Суммарная премия по всем рискам/покрытиям.
 * @property ContainerCollection $RISK
 */
class RISKS extends Container
{
    protected $rules = [
        'BONUS' => ['toDouble', 'required'],
        'RISK' => ['containerCollection:' . RISK::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['BONUS']);

        if ($this->RISK !== null) {
            $this->RISK->toXml($xml, 'RISK');
        }

        return $this;
    }

}