<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Противоугонная система
 *
 * @property string $SECURITY_BRAND - Марка противоугонной системы
 * @property string $SECURITY_MODEL - Модель противоугонной системы
 * @property string $SECURITY_AUTOSTART - Наличие Авто запуска
 */
class SecuritySystem extends Container
{
    protected $rules = [
        'SECURITY_BRAND' => ['toString'],
        'SECURITY_MODEL' => ['toString'],
        'SECURITY_AUTOSTART' => ['toYN'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributesExcept($xml, []); //all into attributes
        return $this;
    }

}