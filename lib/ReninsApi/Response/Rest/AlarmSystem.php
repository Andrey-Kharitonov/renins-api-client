<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Alarm system
 *
 * @property string $brand - марка
 * @property string $Model - модель
 * @property float $MaxPrice - макс. стоимость
 * @property string $OnlyFor
 * @property integer $Type - Тип
 *   0 – ПСС (Поисковая система);
 *   1 – ПУУ (Противоугонная система);
 *   2 – Закладка
 * @property integer $RiskClass - класс риска
 * @property bool $NewBusiness
 */
class AlarmSystem extends Container
{
    protected $rules = [
        'brand' => ['toString'],
        'Model' => ['toString'],
        'MaxPrice' => ['toDouble'],
        'OnlyFor' => ['toString'],
        'Type' => ['toInteger'],
        'RiskClass' => ['toInteger'],
        'NewBusiness' => ['toBoolean'],
    ];
}