<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Информация по объекту страхования (транспортное средство).
 *
 * @property string $TYPE - Тип ТС.
 * @property string $CATEGORY - Категория ТС
 * @property string $BRAND - Марка транспортного средства
 * @property string $MODEL - Модель транспортного средства
 * @property string $MODIFICATION - Модификация транспортного средства
 * @property string $PRICE - Стоимость транспортного средства
 * @property string $POWER - Мощность транспортного средства
 * @property string $YEAR - Год выпуска транспортного средства
 * @property string $BUYING_YEAR - Год приобретения транспортного средства
 * @property string $VIN - VIN транспортного средства
 * @property string $REG_SIGN - Регистрационный знак транспортного средства
 * @property string $COLOR - Цвет транспортного средства
 * @property string $GO_TO_REG_PLACE - ТС следует к месту регистрации
 * @property string $USE_PERIOD - Период использования ТС
 * @property string $CHASSIS - Шасси/рама ТС
 * @property string $BODY_NUMBER - Номер кузова/прицепа ТС
 * @property string $IS_LEASE - ТС сдается в аренду или прокат?
 * @property string $IS_CREDIT - ТС кредитное?
 * @property string $CREDIT_BANK_NAME - Идентификатор банка (код банка в Ренессанс).
 * @property string $LEASING_COMPANY_ID - Идентификатор лизинговой компании (код компании в Ренессанс).
 * @property string $PURPOSE - Цель использования ТС (личная, учебная езда, инкассация, ...)
 * @property integer $GROSS_WEIGHT - Максимальная масса ТС (кг)
 * @property integer $PASSENGER_CAPACITY - Количество пассажирских мест
 * @property string $STATUS - Статус осмотра ТС
 * @property string $MILEAGE - Пробег
 * @property integer $KEY_COUNT - Количество ключей
 * @property string $RIGHT_WHEEL_FLG - Флаг "Правый руль"
 * @property integer $VEHICLE_WEAR - Износ ТС в процентах
 * @property ContainerCollection $EXTRAS - Дополнительное оборудование.
 * @property ContainerCollection $VEHICLE_DOCUMENTS - Документы
 * @property SearchSystem $SEARCH_SYSTEM - Поисковая система
 * @property SecuritySystem $SECURITY_SYSTEM - Противоугонная система
 * @property string $REG_COUNTRY - Страна регистрации ТС
 * @property string $REG_PLACE - Место регистрации ТС
 * @property string $ENGINE_VOLUME - Объем двигателя
 * @property string $ENGINE_TYPE - Тип двигателя
 * @property string $IS_FOREIGN - Признак иностранное или НЕ иностранное ТС. Поле обязательное, если значение в ПТС не из списка ТС определённых в системе СК.
 * @property string $TRANSMISSION_TYPE - Тип коробки передач
 * @property string $VEHICLE_BODY_TYPE - Тип кузова ТС
 */
class Vehicle extends Container
{
    protected $rules = [
        'TYPE' => ['toString', 'required', 'vehicleType:import'],
        'CATEGORY' => ['toString'],
        'BRAND' => ['toString'],
        'MODEL' => ['toString'],
        'MODIFICATION' => ['toString'],
        'PRICE' => ['toString'],
        'POWER' => ['toString'],
        'YEAR' => ['toString'],
        'BUYING_YEAR' => ['toString'],
        'VIN' => ['toString'],
        'REG_SIGN' => ['toString'],
        'COLOR' => ['toString'],
        'GO_TO_REG_PLACE' => ['toYN'],
        'USE_PERIOD' => ['toString'],
        'CHASSIS' => ['toString'],
        'BODY_NUMBER' => ['toString'],
        'IS_LEASE' => ['toYN'],
        'IS_CREDIT' => ['toYN'],
        'CREDIT_BANK_NAME' => ['toString'],
        'LEASING_COMPANY_ID' => ['toString'],
        'PURPOSE' => ['toString'],
        'GROSS_WEIGHT' => ['toInteger', 'min:0'],
        'PASSENGER_CAPACITY' => ['toInteger', 'min:0'],
        'STATUS' => ['toString'],
        'MILEAGE' => ['toString'],
        'KEY_COUNT' => ['toInteger', 'min:0'],
        'RIGHT_WHEEL_FLG' => ['toYN'],
        'VEHICLE_WEAR' => ['toInteger', 'min:0'],
        'EXTRAS' => ['containerCollection:' . Equipment::class],
        'VEHICLE_DOCUMENTS' => ['containerCollection:' . Document::class],
        'SEARCH_SYSTEM' => ['container:' . SearchSystem::class],
        'SECURITY_SYSTEM' => ['container:' . SecuritySystem::class],
        'REG_COUNTRY' => ['toString'],
        'REG_PLACE' => ['toString'],
        'ENGINE_VOLUME' => ['toString'],
        'ENGINE_TYPE' => ['toString', 'engineType'],
        'IS_FOREIGN' => ['toYN'],
        'TRANSMISSION_TYPE' => ['toString', 'transmissionType:import'],
        'VEHICLE_BODY_TYPE' => ['toString', 'vehicleBodyType'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlTagsExcept($xml, ['VEHICLE_DOCUMENTS', 'EXTRAS']);

        if ($this->EXTRAS) {
            $added = $xml->addChild('EXTRAS');
            $this->EXTRAS->toXml($added, 'EQUIPMENT');
        }
        if ($this->VEHICLE_DOCUMENTS) {
            $added = $xml->addChild('VEHICLE_DOCUMENTS');
            $this->VEHICLE_DOCUMENTS->toXml($added, 'DOCUMENT');
        }

        return $this;
    }

}