<?php

namespace ReninsApi\Request\Soap\Calculation;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Вид, серия и номер документа, удостоверяющего личность
 *
 * @property string $DocPerson - тип
 * @property string $Serial - серия
 * @property string $Number - номер
 */
class PersonDocument extends Container
{
    protected $rules = [
        'DocPerson' => ['toString', 'required', 'docType'],
        'Serial' => ['toString', 'length:|10'],
        'Number' => ['toString', 'required', 'length:1|25'],
    ];
}