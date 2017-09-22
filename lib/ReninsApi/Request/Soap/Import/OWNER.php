<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Владелец ТС.
 * todo: доделать по xsd
 *
 * @property string $TYPE - Физическое лицо / Юридическое лицо.
 * @property double $KBM - Коэффициент бонус-малус
 */
class OWNER extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'personType'],
        'KBM' => ['toDouble', 'min:0'],

        'CONTACT' => ['container:' . CONTACT::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['TYPE', 'KBM']);
        $this->toXmlTags($xml, ['CONTACT']);
        return $this;
    }
}
