<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

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
 * @property SomeList $Options
 * @property SomeList $StoaTypes
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
    protected static $rules = [
        'SuppressPercentage' => ['toBoolean'],
        'PercentageAccuracy' => ['toString'],  //unknown type
        'Success' => ['toBoolean', 'required'],
        'b2b_id' => ['toInteger'],
        'AccountNumber' => ['toString'],

        'Messages' => ['containerCollection'],
        'Risks' => ['container'],
        'Total' => ['container'],
        'Currency' => ['container'],
        'User' => ['container'],
        'InsuranceObjects' => ['containerCollection'],
        'Options' => ['container'],
        'StoaTypes' => ['container'],
        'Deductible' => ['container'],
        'KeysDocsDeductible' => ['toString'], //unknown type
        'DriversDeductible' => ['toString'], //unknown type
        'FourLossDeductible' => ['toString'], //unknown type
        'TotalDestruction' => ['toBoolean'],
        'LossCount' => ['toString'], //unknown type
        'CoefLoss' => ['toString'], //unknown type
        'CoefProlongation' => ['toString'], //unknown type
    ];
}