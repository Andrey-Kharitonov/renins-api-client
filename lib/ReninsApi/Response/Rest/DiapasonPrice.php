<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;

/**
 * Мин и макс стоимость ТС
 *
 * @property integer $MinValue
 * @property integer $MaxValue
 */
class DiapasonPrice extends Container
{
    protected $rules = [
        'MinValue' => ['toInteger'],
        'MaxValue' => ['toInteger'],
    ];
}