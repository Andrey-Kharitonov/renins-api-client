<?php

namespace ReninsApi\Request\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Физическое лицо
 * todo: доделать по xsd
 *
 * @property string $LAST_NAME
 * @property string $FIRST_NAME
 * @property string $MIDDLE_NAME
 * @property string $BIRTH_DATE
 * @property string $HOME_PHONE
 * @property string $RESIDENT
 * @property ContainerCollection $CONTACT_ADDRESSES
 * @property ContainerCollection $CONTACT_DOCUMENTS
 */
class CONTACT extends Container
{
    protected $rules = [
        'LAST_NAME' => ['toString'],
        'FIRST_NAME' => ['toString'],
        'MIDDLE_NAME' => ['toString'],
        'BIRTH_DATE' => ['toString', 'date'],
        'HOME_PHONE' => ['toString'],
        'RESIDENT' => ['toYN'],
        'CONTACT_ADDRESSES' => ['containerCollection:' . ADDRESS::class],
        'CONTACT_DOCUMENTS' => ['containerCollection:' . DOCUMENT::class],
    ];
}