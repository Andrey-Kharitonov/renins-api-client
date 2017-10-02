<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;

/**
 * Блок для ОСАГО
 *
 * @property integer $CalculationType - Тип расчёта премии ОСАГО. Default: 1.
 *   1 - Полный рассчёт (осуществляется запрос в РСА)
 *   2 - предварительный рассчёт
 * @property string $TicketCar - Выполнить запрос на ТО в РСА. Default: NO.
 * @property string $KNEnable - Применяется коэффициент КН (при наличии грубых нарушений). Default: NO.
 * @property string $SeasonUse - Сезонное использование ТС (для специальной техники). Default: NO.
 * @property string $Transit - Следует к месту регистрации. Default: NO.
 * @property string $RegistrationPlace - Регион регистрации ТС для ЮЛ, либо, регион регистрации собственника ТС для ФЛ, определяет тарифную зону для ОСАГО.
 * @property string $RegistrationCountry - страна регистрации (для ОСАГО). Варианты: "Россия", "Иные государства".
 * @property double $Kbm - Коэффициент бонус-малус
 * @property CalcKBMRequest $CalcKBMRequest
 */
class Osago extends Container
{
    protected $rules = [
        'CalculationType' => ['toInteger', 'in:1|2'],
        'TicketCar' => ['toLogical'],

        'KNEnable' => ['toLogical'],
        'SeasonUse' => ['toLogical'],
        'Transit' => ['toLogical'],
        'RegistrationPlace' => ['toString', 'required', 'notEmpty'],
        'RegistrationCountry' => ['toString', 'required', 'in:Россия|Иные государства'],
        'Kbm' => ['toDouble', 'required', 'kbm'],
        'CalcKBMRequest' => ['container:' . CalcKBMRequest::class, 'required'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['CalculationType', 'TicketCar']);
        $this->toXmlTagsExcept($xml, ['CalculationType', 'TicketCar']);

        return $this;
    }
}