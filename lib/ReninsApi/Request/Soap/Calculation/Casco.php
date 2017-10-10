<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Блок для КАСКО
 *
 * @property ContainerCollection $Stoa - Порядок выплаты
 * @property Deductible $Deductible - Взаимоисключающие франшизы
 * @property string $KeysDocsDeductible - Франшиза по угону с ключами/документами. По умолчанию уже включена. (отключение данной франшизы значительно повлияет на премию).
 * @property string $Uts - Опция "Утрата товарной стоимости"
 * @property PersonalDeductible $PersonalDeductible - персональная франшиза
 * @property string $Packet - Итоговый, выбранный пакет котировки
 * @property string $TotalDestruction - Условие "Полная гибель" - специальное условие, которое обозначает, что возмещение возможно получить, только при полной гибели автомобиля и больше ни при каких условиях.
 *   При страховании только полной гибели нельзя не отмечать страхование риска "Ущерб", так как непосредственно риска "тоталь" в наших правилах нет.
 *   Тоталь - это одна из составляющих риска "Ущерб" и страхование от полной гибели по умолчанию подразумевается при страховании риска "Ущерб".
 *   Когда в договоре мы отмечаем обе галочки (ущерб на условиях полной гибели), получаем, что застрахован риск "Ущерб", но только при условии полной гибели, т.к. условия договора первичны по отношению к условиям Правил.
 * @property string $BankName - Идентификатор банка (код банка в Ренессанс)
 * @property string $LeasingID - Идентификатор лизинговой компании (код компании в Ренессанс)
 * @property string $LeasingEnabled - Лизинг
 * @property string $BankEnabled - Кредитный договор
 * @property ContainerCollection $CustomOptions - Дополнительные опции, если не используются пакеты
 * @property string $NewClient - Новый клиент, если страхователь юр. лицо
 * @property string $GAPEnabled - Включить страхование GAP (полная стоимость).
 * @property string $B2BDiscount - скидка при оформлении через В2В
 * @property double $Rebate - размер скидки за счёт КВ (в %)
 * @property string $DivisionForCalc - Регион расчёта (используется при отличии от Division)
 * @property string $DivisionForDelivery - Город доставки полиса
 * @property string $TradeINEnabled - признак, что машина из Trade IN
 * @property string $LosslessInsurer - Безубыточный страхователь
 * @property double $HomingCoef - Коэффициент самонаведения
 * @property Telematics $Telematics - Данные по телематике.
 */
class Casco extends Container
{
    protected $rules = [
        'Stoa' => ['containerCollection:' . StoaType::class, 'required', 'notEmpty'],
        'Deductible' => ['container:' . Deductible::class],
        'KeysDocsDeductible' => ['toLogical'],
        'Uts' => ['toLogical'],
        'PersonalDeductible' => ['container:' . PersonalDeductible::class],
        'Packet' => ['toString'],
        'TotalDestruction' => ['toLogical'],
        'BankName' => ['toString'],
        'LeasingID' => ['toString'],
        'LeasingEnabled' => ['toLogical'],
        'BankEnabled' => ['toLogical'],
        'CustomOptions' => ['containerCollection:' . Option::class, 'length:|7'],
        'NewClient' => ['toLogical'],
        'GAPEnabled' => ['toLogical'],
        'B2BDiscount' => ['toLogical'],
        'Rebate' => ['toDouble'],
        'DivisionForCalc' => ['toString'],
        'DivisionForDelivery' => ['toString'],
        'TradeINEnabled' => ['toLogical'],
        'LosslessInsurer' => ['toLogical'],
        'HomingCoef' => ['toDouble'],
        'Telematics' => ['container:' . Telematics::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        //All tags are typical, except Stoa
        $this->toXmlTagsExcept($xml, ['Stoa', 'CustomOptions']);

        //Stoa with custom children name
        if ($this->Stoa) {
            $added = $xml->addChild('Stoa');
            $this->Stoa->toXml($added, 'StoaType');
        }

        //CustomOptions with custom children name
        if ($this->CustomOptions) {
            $added = $xml->addChild('CustomOptions');
            $this->CustomOptions->toXml($added, 'Option');
        }

        return $this;
    }
}