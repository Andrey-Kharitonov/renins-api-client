<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Дополнительная информация по котировке (уникальная для каждого объекта страхования)
 *
 * @property double $POLICY_KBM - Коэффициент бонус-малус (получен через сервис РСА), обязательно для ОСАГО.
 * @property string $POLICY_SERIES - Серия полиса, элемент используется для ОСАГО.
 * @property string $POLICY_NUMBER - Номер полиса, элемент используется для всех страховых продуктов.
 *   Для КАСКО его необходимо получить методом GetPolicyNumber. Для ОСАГО это 10-значное число.
 * @property string $TICKET_NUMBER - Номер квитанции A7
 * @property string $BSO_NUMBER - Номер БСО.
 *   КАСКО: Без БСО не удасться распечать полис. Для тестовой среды "1234567".
 *   ОСАГО: Для тестовой среды указывать не нужно.
 * @property string $CREATION_DATE - Дата создания котировки.
 * @property string $INS_DATE_FROM - Период страхования с ...
 * @property string $INS_TIME_FROM - Время страхования с ...
 * @property string $INS_DATE_TO - Период страхования по ...
 *   В ОСАГО датой начала страхования почему-то считается SALE_DATE и неважно что стоит в INS_DATE_FROM.
 *   Соответственно тут должно быть SALE_DATE + 1 год - 1 день
 * @property string $INS_TIME_TO - Время страхования по ...
 * @property string $INSURANCE_SUM - Сумма расчета
 * @property string $CURRENCY - Валюта расчета.
 * @property int $INS_DURATION - Срок страхования (месяцев).
 * @property int $INS_PERIOD_USE - Период использования (месяцев).
 * @property string $NO_DOING - Применение коэффициента КН (при наличии грубых нарушений).
 * @property string $SPECIAL_NOTES - Особые условия страхования. Используется для указания своего текста особых условий, если кредитный договор согласован, но указан «Иной банк».
 * @property string $COMMENT - Комментарии, выводимые на печатной форме полиса ОСАГО, в разделе «Особые отметки».
 * @property Franchise $FRANCHISE - Франшиза
 * @property string $KEYS_DOCS_FRANCHISE - Франшиза по угону с ключами/документами.
 * @property ContainerCollection $PERIODS - Периоды страхования
 * @property string $PREV_INS_TITLE - Предыдущий страховщик.
 * @property string $PREV_INS_POLICY_SERIAL - Серия предыдущего полиса страхования.
 * @property string $PREV_INS_POLICY_NUMBER - Номер предыдущего полиса страхования.
 * @property int $PREV_EVENT_COUNT - Количество страховых случаев по предыдущему полису страхования.
 * @property string $PREV_POLICY_STATE - Статус предыдущего договора - "Украден"/"Утрачен"/"Приложен".
 * @property string $PREV_POLICY_CHANGES - Основание для внесения изменений.
 * @property string $PREV_POLICY_DOCUMENTS - Документы, которые могут подтвердить основание для внесения изменений.
 * @property string $CROSS_SELL_INS_POLICY_SERIAL - Серия кросс-проданного полиса страхования.
 * @property string $CROSS_SELL_INS_POLICY_NUMBER - Номер кросс-проданного полиса страхования.
 * @property Risks $RISKS - Риски/покрытия
 * @property Payments $PAYMENTS - Данные по плану платежей
 * @property ModesOfCompensation $MODES_OF_COMPENSATION - Способы возмещения.
 * @property AdditionalOptions $ADDITIONAL_OPTIONS - Дополнительные опции.
 * @property DocumentOfPayment $DOCUMENT_OF_PAYMENT - Платежный документ.
 * @property string $INS_REPRESENT - Представитель страховщика (ФИО).
 * @property string $INS_REPRESENT_MANDATE - Доверенность представителя страховщика (номер).
 * @property string $INS_REPRESENT_MANDATE_DATE - Дата доверенности представителя страховщика
 * @property ContainerCollection $ADDITIONAL_QUESTIONS - Дополнительные вопросы.
 * @property string $VIP_CARD_NUMBER - Номер карты VIP-клиента.
 * @property string $BENEFICIARY_IN_COMMENT - Выгодоприобретатель указывается в поле Особые условия.
 * @property string $TOTALLY - На условиях Полная гибель.
 * @property string $DOWN_PAYMENT - В рассрочку (нет, 50%/50% в течение трех месяцев, 40%/20%/20%20% в течении года)
 * @property string $KN_COEFF_USING - применение коэффициента КН (при наличии грубых нарушений)
 * @property PreInsuranceInspection $PRE_INSURANCE_INSPECTION - Предстраховой осмотр
 * @property CurrentInsCompany $CURRENT_INS_COMPANY - Текущий страховщик по договору страхования ТС (обязательно для GAP).
 * @property string $PRODUCT_SIEBEL - Страховой продукт, тип страхового продукта (ОСАГО, КАСКО, ДАГО)
 */
class PrivateQuoteInfo extends Container
{
    protected $rules = [
        'POLICY_KBM' => ['toDouble', 'kbm'],
        'POLICY_SERIES' => ['toString'],
        'POLICY_NUMBER' => ['toString', 'required', 'notEmpty'],
        'TICKET_NUMBER' => ['toString'],
        'BSO_NUMBER' => ['toString'],
        'CREATION_DATE' => ['toString', 'date'],
        'INS_DATE_FROM' => ['toString', 'date'],
        'INS_TIME_FROM' => ['toString', 'time'],
        'INS_DATE_TO' => ['toString', 'date'],
        'INS_TIME_TO' => ['toString', 'time'],
        'INSURANCE_SUM' => ['toString'],
        'CURRENCY' => ['toString', 'currency'],
        'INS_DURATION' => ['toInteger', 'min:1'],
        'INS_PERIOD_USE' => ['toInteger', 'min:1'],
        'NO_DOING' => ['toYN'],
        'SPECIAL_NOTES' => ['toString'],
        'COMMENT' => ['toString'],
        'FRANCHISE' => ['container:' . Franchise::class],
        'KEYS_DOCS_FRANCHISE' => ['toString'],
        'PERIODS' => ['containerCollection:' . Period::class, 'length:|3'],
        'PREV_INS_TITLE' => ['toString'],
        'PREV_INS_POLICY_SERIAL' => ['toString'],
        'PREV_INS_POLICY_NUMBER' => ['toString'],
        'PREV_EVENT_COUNT' => ['toInteger', 'min:0'],
        'PREV_POLICY_STATE' => ['toString', 'in:Украден|Утрачен|Приложен'],
        'PREV_POLICY_CHANGES' => ['toString'],
        'PREV_POLICY_DOCUMENTS' => ['toString'],
        'CROSS_SELL_INS_POLICY_SERIAL' => ['toString'],
        'CROSS_SELL_INS_POLICY_NUMBER' => ['toString'],
        'RISKS' => ['container:' . Risks::class],
        'PAYMENTS' => ['container:' . Payments::class],
        'MODES_OF_COMPENSATION' => ['container:' . ModesOfCompensation::class],
        'ADDITIONAL_OPTIONS' => ['container:' . AdditionalOptions::class],
        'DOCUMENT_OF_PAYMENT' => ['container:' . DocumentOfPayment::class],
        'INS_REPRESENT' => ['toString'],
        'INS_REPRESENT_MANDATE' => ['toString'],
        'INS_REPRESENT_MANDATE_DATE' => ['toString'],
        'ADDITIONAL_QUESTIONS' => ['containerCollection:' . Question::class],
        'VIP_CARD_NUMBER' => ['toString'],
        'BENEFICIARY_IN_COMMENT' => ['toYN'],
        'TOTALLY' => ['toYN'],
        'DOWN_PAYMENT' => ['toString', 'in:нет|50%/50% в течение трех месяцев|40%/20%/20%20% в течении года'],
        'KN_COEFF_USING' => ['toYN'],
        'PRE_INSURANCE_INSPECTION' => ['container:' . PreInsuranceInspection::class], //PRE-INSURANCE_INSPECTION!!!
        'CURRENT_INS_COMPANY' => ['container:' . CurrentInsCompany::class],
        'PRODUCT_SIEBEL' => ['toString', 'in:ОСАГО|КАСКО|ДАГО'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        //Все, кроме PRE_INSURANCE_INSPECTION
        $this->toXmlTagsExcept($xml, ['PRE_INSURANCE_INSPECTION', 'ADDITIONAL_QUESTIONS']);

        //PRE_INSURANCE_INSPECTION
        if ($this->PRE_INSURANCE_INSPECTION !== null) {
            $added = $xml->addChild('PRE-INSURANCE_INSPECTION');
            $this->PRE_INSURANCE_INSPECTION->toXml($added);
        }

        //ADDITIONAL_QUESTIONS
        if ($this->ADDITIONAL_QUESTIONS) {
            $added = $xml->addChild('ADDITIONAL_QUESTIONS');
            $this->ADDITIONAL_QUESTIONS->toXml($added, 'QUESTION');
        }

        return $this;
    }
}