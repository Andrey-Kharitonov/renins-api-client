<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;

/**
 * Calc answer
 *
 * @property string $SuppressPercentage
 * @property string $PercentageAccuracy
 * @property string $Success
 * @property int $b2b_id
 * @property string $AccountNumber
 *
 */
class CalcResults extends Container
{
    protected static $rules = [
        'SuppressPercentage' => ['toBoolean'],
        'PercentageAccuracy' => ['toString'],
        'Success' => ['toBoolean', 'required'],
        'b2b_id' => ['toInteger'],
        'AccountNumber' => ['toString'],
    ];

    protected $SuppressPercentage;
    protected $PercentageAccuracy;
    protected $Success;
    protected $b2b_id;
    protected $AccountNumber;
}