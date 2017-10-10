<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Информация по объекту страхования (транспортное средство).
 * todo: доделать по xsd
 *
 * @property string $TYPE - Тип ТС.
 * @property string $BRAND - Марка транспортного средства
 * @property string $MODEL - Модель транспортного средства
 * @property string $PRICE - Стоимость транспортного средства
 * @property string $POWER - Мощность транспортного средства
 * @property string $YEAR - Год выпуска транспортного средства
 * @property string $VIN - VIN транспортного средства
 * @property string $REG_SIGN - Регистрационный знак транспортного средства
 * @property string $COLOR - Цвет транспортного средства
 * @property string $IS_LEASE - ТС сдается в аренду или прокат?
 * @property string $IS_CREDIT - ТС кредитное?
 * @property string $PURPOSE - Цель использования ТС (личная, учебная езда, инкассация, ...)
 * @property integer $KEY_COUNT - Количество ключей
 * @property string $ENGINE_VOLUME - Объем двигателя
 * @property string $ENGINE_TYPE - Тип двигателя
 * @property string $TRANSMISSION_TYPE - Тип коробки передач
 * @property string $VEHICLE_BODY_TYPE - Тип кузова ТС
 * @property ContainerCollection $VEHICLE_DOCUMENTS - Документы
 * @property ContainerCollection $EXTRAS - Дополнительное оборудование.
 */
class Vehicle extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'vehicleType:import'],
        'BRAND' => ['toString'],
        'MODEL' => ['toString'],
        'PRICE' => ['toString'],
        'POWER' => ['toString'],
        'YEAR' => ['toString'],
        'VIN' => ['toString'],
        'REG_SIGN' => ['toString'],
        'COLOR' => ['toString'],
        'IS_LEASE' => ['toYN'],
        'IS_CREDIT' => ['toYN'],
        'PURPOSE' => ['toString'],
        'KEY_COUNT' => ['toInteger', 'min:0'],
        'ENGINE_VOLUME' => ['toString'],
        'ENGINE_TYPE' => ['toString', 'engineType'],
        'TRANSMISSION_TYPE' => ['toString', 'transmissionType'],
        'VEHICLE_BODY_TYPE' => ['toString', 'vehicleBodyType'],
        'VEHICLE_DOCUMENTS' => ['containerCollection:' . Document::class],
        'EXTRAS' => ['containerCollection:' . Equipment::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlTagsExcept($xml, ['VEHICLE_DOCUMENTS', 'EXTRAS']);

        if ($this->VEHICLE_DOCUMENTS) {
            $added = $xml->addChild('VEHICLE_DOCUMENTS');
            $this->VEHICLE_DOCUMENTS->toXml($added, 'DOCUMENT');
        }
        if ($this->EXTRAS) {
            $added = $xml->addChild('EXTRAS');
            $this->EXTRAS->toXml($added, 'EQUIPMENT');
        }

        return $this;
    }

}