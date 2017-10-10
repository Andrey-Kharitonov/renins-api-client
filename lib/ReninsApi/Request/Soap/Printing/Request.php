<?php

namespace ReninsApi\Request\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Some request.
 * PartnerName and PartnerUId will be replaced! Don't specify it.
 *
 * @property string $PartnerName - Код партнера (не указывать, будет заполнен автоматом)
 * @property string $PartnerUId - Uid партнера (не указывать, будет заполнен автоматом)
 * @property string $AccountNumber - Код котировки
 * @property string $PrintToken - Токен печати. Обязателен для печати оригинала полиса.
 * @property ContainerCollection $printingParamsItems
 * @property boolean $isPrintAsOneDocument - В один PDF?
 */
class Request extends Container
{
    protected $rules = [
        'PartnerName' => ['toString'],
        'PartnerUId' => ['toString'],
        'AccountNumber' => ['toString', 'required', 'notEmpty'],
        'PrintToken' => ['toString'],
        'printingParamsItems' => ['containerCollection:' . PrintingParams::class],
        'isPrintAsOneDocument' => ['toBoolean'],
    ];
}