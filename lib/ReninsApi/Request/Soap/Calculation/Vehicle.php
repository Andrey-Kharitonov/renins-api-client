<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Транспортное средство
 *
 * @property string $Manufacturer - Марка(бренд)
 * @property string $Model - Модель
 * @property int $Year - Год
 * @property double $Cost - Цена
 * @property string $Type
 * @property AntiTheftDeviceInfo $AntiTheftDeviceInfo - Поисковая система
 * @property PUUDeviceInfo $PUUDeviceInfo - Противоугонная система
 * @property string $RightWheel - Наличие правого руля
 * @property int $ManufacturerType - Производитель ТС (0 - Иностранное ТС, 1 - Отечественное)
 * @property string $IsNew - Новое ТС?
 * @property string $IsTaxi - Используется как Такси?
 * @property int $Power - Мощность ТС
 * @property int $GrossWeight - Максимальная масса ТС (кг)
 * @property int $PassangerCapacity - Количество пассажирских мест
 * @property string $Category - Категория ТС
 * @property string $CarBodyType - Тип кузова
 * @property string $TransmissionType - Тип КПП
 * @property string $EngineType - Тип КПП
 * @property CarIdent $CarIdent - Идентификатор ТС (необходимо наличие хотя бы одного дочернего элемента).
 * @property string $UseTrailer - Эксплуатируется вместе с прицепом?
 */
class Vehicle extends Container
{
    protected $rules = [
        'Manufacturer' => ['toString', 'required', 'notEmpty'],
        'Model' => ['toString', 'required', 'notEmpty'],
        'Year' => ['toInteger', 'required', 'notEmpty'],
        'Cost' => ['toDouble', 'required', 'notEmpty', 'min:0'],
        'Type' => ['toString', 'required', 'vehicleType'],
        'AntiTheftDeviceInfo' => ['container:' . AntiTheftDeviceInfo::class],
        'PUUDeviceInfo' => ['container:' . PUUDeviceInfo::class],
        'RightWheel' => ['toLogical'],
        'ManufacturerType' => ['toInteger', 'required', 'in:0|1'],
        'IsNew' => ['toLogical'],
        'IsTaxi' => ['toLogical'],
        'Power' => ['toInteger', 'required', 'notEmpty'],
        'GrossWeight' => ['toInteger', 'min:0'],
        'PassangerCapacity' => ['toInteger', 'min:0'],
        'Category' => ['toString', 'vehicleCategory'],
        'CarBodyType' => ['toString', 'required', 'vehicleBodyType'],
        'TransmissionType' => ['toString', 'transmissionType'],
        'EngineType' => ['toString', 'engineType'],
        'CarIdent' => ['container:' . CarIdent::class],
        'UseTrailer' => ['toLogical'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlTagsExcept($xml, ['RightWheel']);
        if ($this->RightWheel) {
            $xml->addChild('Right-wheel', $this->RightWheel);
        }

        return $this;
    }
}