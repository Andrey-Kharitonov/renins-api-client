<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Some request.
 *
 * @property string $PartnerName - Код партнера (не указывать, будет заполнен автоматом)
 * @property string $PartnerUId - Uid партнера (не указывать, будет заполнен автоматом)
 * @property string $AccountNumber - Код котировки
 */
class Request extends Container
{
    protected $rules = [
        'PartnerName' => ['toString'],
        'PartnerUId' => ['toString'],
        'AccountNumber' => ['toString'],
    ];
}