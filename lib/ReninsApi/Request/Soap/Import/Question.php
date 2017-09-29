<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Вопрос
 *
 * @property string $Q_NAME - Текст вопроса
 * @property string $Q_ANSWER - Ответ на вопрос
 */
class Question extends Container
{
    protected $rules = [
        'Q_NAME' => ['toString', 'required', 'notEmpty'],
        'Q_ANSWER' => ['toString', 'required', 'notEmpty'],
    ];
}