<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Main block for CASCO
 *
 * @property ContainerCollection $Stoa
 * @property Deductible $Deductible
 * @property string|bool $KeysDocsDeductible
 * @property string|bool $Uts
 * @property PersonalDeductible $PersonalDeductible
 * @property string $Packet
 * @property string|bool $TotalDestruction
 * @property string $BankName
 * @property string $LeasingID
 * @property string|bool $LeasingEnabled
 * @property string|bool $BankEnabled
 * @property ContainerCollection $CustomOptions
 * @property string|bool $NewClient
 * @property string|bool $GAPEnabled
 * @property string|bool $B2BDiscount
 * @property double $Rebate
 * @property int $Division
 * @property string $DivisionForCalc
 * @property string $DivisionForDelivery
 * @property string|bool $TradeINEnabled
 * @property string|bool $LosslessInsurer
 * @property double $HomingCoef
 * @property Telematics $Telematics
 */
class Casco extends Container
{
    protected static $rules = [
        'Stoa' => ['containerCollection', 'required', 'notEmpty'],
        'Deductible' => ['toContainer'],
        'KeysDocsDeductible' => ['toLogical'],
        'Uts' => ['toLogical'],
        'PersonalDeductible' => ['toContainer'],
        'Packet' => ['toString'],
        'TotalDestruction' => ['toLogical'],
        'BankName' => ['toString'],
        'LeasingID' => ['toString'],
        'LeasingEnabled' => ['toLogical'],
        'BankEnabled' => ['toLogical'],
        'CustomOptions' => ['containerCollection', 'length:,7'],
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
        'Telematics' => ['toContainer'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        //Stoa with custom children name
        $added = $xml->addChild('Stoa');
        $this->Stoa->toXml($added, 'StoaType');

        //Other tags are typical
        $rules = array_diff_key(static::$rules, ['Stoa' => true]);
        $this->toXmlTags($xml, array_keys($rules));

        return $this;
    }

    protected $Stoa;
    protected $Deductible;
    protected $KeysDocsDeductible;
    protected $Uts;
    protected $PersonalDeductible;
    protected $Packet;
    protected $TotalDestruction;
    protected $BankName;
    protected $LeasingID;
    protected $LeasingEnabled;
    protected $BankEnabled;
    protected $CustomOptions;
    protected $NewClient;
    protected $GAPEnabled;
    protected $B2BDiscount;
    protected $Rebate;
    protected $Division;
    protected $DivisionForCalc;
    protected $DivisionForDelivery;
    protected $TradeINEnabled;
    protected $LosslessInsurer;
    protected $HomingCoef;
    protected $Telematics;
}