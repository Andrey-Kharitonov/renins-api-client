<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Дополнительная информация по котировке (уникальная для каждого объекта страхования)
 * todo: доделать по xsd
 *
 * @property string $POLICY_NUMBER - Номер полиса, элемент используется для всех страховых продуктов.
 * @property string $BSO_NUMBER - Номер БСО.
 * @property string $CREATION_DATE - Дата создания котировки.
 * @property string $INS_DATE_FROM - Период страхования с ...
 * @property string $INS_TIME_FROM - Время страхования с ...
 * @property string $INS_DATE_TO - Период страхования по ...
 * @property string $INS_TIME_TO - Время страхования по ...
 * @property string $INSURANCE_SUM - Сумма расчета
 * @property DOCUMENT_OF_PAYMENT $DOCUMENT_OF_PAYMENT - Платежный документ.
 * @property string $TOTALLY - На условиях Полная гибель.
 * @property PRE_INSURANCE_INSPECTION $PRE_INSURANCE_INSPECTION - Предстраховой осмотр
 * @property RISKS $RISKS - Риски/покрытия
 */
class PRIVATE_QUOTE_INFO extends Container
{
    protected $rules = [
        'POLICY_NUMBER' => ['toString', 'required', 'notEmpty'],
        'BSO_NUMBER' => ['toString'],
        'CREATION_DATE' => ['toString', 'date'],
        'INS_DATE_FROM' => ['toString', 'date'],
        'INS_TIME_FROM' => ['toString', 'time'],
        'INS_DATE_TO' => ['toString', 'date'],
        'INS_TIME_TO' => ['toString', 'time'],
        'INSURANCE_SUM' => ['toString'],
        'CURRENCY' => ['toString', 'currency'],
        'INS_DURATION' => ['toInteger', 'min:1'],
        'DOCUMENT_OF_PAYMENT' => ['container:' . DOCUMENT_OF_PAYMENT::class],
        'TOTALLY' => ['toYM'],
        'PRE_INSURANCE_INSPECTION' => ['container:' . PRE_INSURANCE_INSPECTION::class], //PRE-INSURANCE_INSPECTION!!!
        'RISKS' => ['container:' . RISKS::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        //Все, кроме PRE_INSURANCE_INSPECTION
        $this->toXmlTagsExcept($xml, ['PRE_INSURANCE_INSPECTION']);

        //PRE_INSURANCE_INSPECTION
        $value = $this->PRE_INSURANCE_INSPECTION;
        if ($value !== null) {
            $added = $xml->addChild('PRE-INSURANCE_INSPECTION');
            $value->toXml($added);
        }

        return $this;
    }
}