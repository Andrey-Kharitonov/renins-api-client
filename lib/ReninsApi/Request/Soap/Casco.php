<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Main block for CASCO
 *
 * @property ContainerCollection $Stoa
 * @property Deductible $Deductible
 * @property string $KeysDocsDeductible
 * @property string $Uts
 * @property PersonalDeductible $PersonalDeductible
 * @property string $Packet
 * @property string $TotalDestruction
 * @property string $BankName
 * @property string $LeasingID
 * @property string $LeasingEnabled
 * @property string $BankEnabled
 * @property ContainerCollection $CustomOptions
 * @property string $NewClient
 * @property string $GAPEnabled
 * @property string $B2BDiscount
 * @property double $Rebate
 * @property int $Division
 * @property string $DivisionForCalc
 * @property string $DivisionForDelivery
 * @property string $TradeINEnabled
 * @property string $LosslessInsurer
 * @property double $HomingCoef
 * @property Telematics $Telematics
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
        'Division' => ['toInteger'],
        'DivisionForCalc' => ['toString'],
        'DivisionForDelivery' => ['toString'],
        'TradeINEnabled' => ['toLogical'],
        'LosslessInsurer' => ['toLogical'],
        'HomingCoef' => ['toDouble'],
        'Telematics' => ['container:' . Telematics::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        //Stoa with custom children name
        $added = $xml->addChild('Stoa');
        $this->Stoa->toXml($added, 'StoaType');

        //Other tags are typical
        $rules = array_diff_key($this->rules, ['Stoa' => true]);
        $this->toXmlTags($xml, array_keys($rules));

        return $this;
    }
}