<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Helpers\Utils;
use ReninsApi\Request\Container;

/**
 * Основной запрос для получения расчета
 *
 * @property int $type - default 0. Тип запроса. 1 - с котировкой
 * @property string $uid - GUID сообщения, используется техподдержкой для поиска информации о запросах и анализа ситуаций.
 * @property Policy $Policy
 */
class Request extends Container
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