<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Deductible;

/**
 * Calc results
 *
 * @property string $SuppressPercentage
 * @property string $PercentageAccuracy
 * @property string $Success
 * @property int $b2b_id
 * @property string $AccountNumber
 * @property ContainerCollection $Messages
 * @property Risks $Risks
 * @property Total $Total
 * @property Currency $Currency
 * @property User $User
 * @property ContainerCollection $InsuranceObjects
 * @property Options $Options
 * @property StoaTypes $StoaTypes
 * @property Deductible $Deductible
 * @property string $KeysDocsDeductible
 * @property string $DriversDeductible
 * @property string $FourLossDeductible
 * @property string $TotalDestruction
 * @property string $LossCount
 * @property string $CoefLoss
 * @property string $CoefProlongation
 */
class CalcResults extends Container
{
    protected $rules = [
        'SuppressPercentage' => ['toBoolean'],
        'PercentageAccuracy' => ['toString'],  //unknown type
        'Success' => ['toBoolean', 'required'],
        'b2b_id' => ['toInteger'],
        'AccountNumber' => ['toString'],

        'Messages' => ['containerCollection:' . Message::class],
        'Risks' => ['container:' . Risks::class],
        'Total' => ['container:' . Total::class],
        'Currency' => ['container:' . Currency::class],
        'User' => ['container:' . User::class],
        'InsuranceObjects' => ['containerCollection:' . InsuranceObject::class],
        'Options' => ['container:' . Options::class],
        'StoaTypes' => ['container:' . StoaTypes::class],
        'Deductible' => ['container:' . Deductible::class],
        'KeysDocsDeductible' => ['toString'], //unknown type
        'DriversDeductible' => ['toString'], //unknown type
        'FourLossDeductible' => ['toString'], //unknown type
        'TotalDestruction' => ['toBoolean'],
        'LossCount' => ['toString'], //unknown type
        'CoefLoss' => ['toString'], //unknown type
        'CoefProlongation' => ['toString'], //unknown type
    ];

    public function fromXml(\SimpleXMLElement $xml) {
        $this->fromXmlAttributes($xml, ['SuppressPercentage', 'PercentageAccuracy', 'Success', 'b2b_id', 'AccountNumber']);

        if ($xml->Messages) {
            $this->Messages = ContainerCollection::createFromXml($xml->Messages[0], Message::class);
        }
        if ($xml->Risks) {
            $this->Risks = Risks::createFromXml($xml->Risks[0]);
        }
        if ($xml->Total) {
            $this->Total = Total::createFromXml($xml->Total[0]);
        }
        if ($xml->Currency) {
            $this->Currency = Currency::createFromXml($xml->Currency[0]);
        }
        if ($xml->User) {
            $this->User = User::createFromXml($xml->User[0]);
        }
        if ($xml->InsuranceObjects) {
            $this->InsuranceObjects = ContainerCollection::createFromXml($xml->InsuranceObjects[0], InsuranceObject::class);
        }
        if ($xml->Options) {
            $this->Options = Options::createFromXml($xml->Options[0]);
        }
        if ($xml->StoaTypes) {
            $this->StoaTypes = StoaTypes::createFromXml($xml->StoaTypes[0]);
        }
        if ($xml->Deductible) {
            $this->Deductible = Deductible::createFromXml($xml->Deductible[0]);
        }

        $this->fromXmlTags($xml, ['KeysDocsDeductible', 'DriversDeductible', 'FourLossDeductible', 'TotalDestruction', 'LossCount', 'CoefLoss', 'CoefProlongation']);

        return $this;
    }
}