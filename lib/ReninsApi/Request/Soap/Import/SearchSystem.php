<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Поисковая система
 *
 * @property string $SEARCH_BRAND - Марка поисковой системы
 * @property string $SEARCH_MODEL - Модель поисковой системы
 * @property string $TRACKER_BRAND - Марка закладки
 * @property string $TRACKER_MODEL - Модель закладки
 */
class SearchSystem extends Container
{
    protected $rules = [
        'SEARCH_BRAND' => ['toString'],
        'SEARCH_MODEL' => ['toString'],
        'TRACKER_BRAND' => ['toString'],
        'TRACKER_MODEL' => ['toString'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributesExcept($xml, []); //all into attributes
        return $this;
    }

}