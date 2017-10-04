<?php
namespace ReninsApi\Response\Soap\Import;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Soap\Message;
use ReninsApi\Response\Soap\PolicyDocumentType;

/**
 * ImportPolicy results
 *
 * @property string $Error - Общая ошибка
 * @property string $ErrorCode - ?
 * @property string $PolicyId - Id полиса в B2B. Не пустое значение означает успешность ответа.
 * @property string $ProlongatedPolicyId - ?
 * @property ContainerCollection $Messages - Список ошибок. Также содержится в конкатинированном виде в Error
 *   Может быть не заполнено даже при непустом Error.
 * @property string $AccountNumber - Номер котировки. Присутствует вместе с PolicyId
 * @property string $PrintToken - Токен печати. Присутствует вместе с PolicyId
 * @property ContainerCollection $AvailableDocumentTypes - Список доступных печатных форм. Присутствует вместе с PolicyId
 */
class ImportPolicyResult extends Container
{
    protected $rules = [
        'Error' => ['toString'],
        'ErrorCode' => ['toString'],
        'PolicyId' => ['toString'],
        'ProlongatedPolicyId' => ['toString'],
        'Messages' => ['containerCollection:' . Message::class],
        'AccountNumber' => ['toString'],
        'PrintToken' => ['toString'],
        'AvailableDocumentTypes' => ['containerCollection:' . PolicyDocumentType::class],
    ];

    public function fromObject($obj) {
        $this->fromObjectOnly($obj, ['Error', 'ErrorCode', 'PolicyId', 'ProlongatedPolicyId', 'AccountNumber', 'PrintToken']);

        if (!empty($obj->Messages) && !empty($obj->Messages->Message)) {
            $this->Messages = ContainerCollection::createFromObject($obj->Messages->Message, Message::class);
        }

        if (!empty($obj->AvailableDocumentTypes) && !empty($obj->AvailableDocumentTypes->PolicyDocumentType)) {
            $this->AvailableDocumentTypes = ContainerCollection::createFromObject($obj->AvailableDocumentTypes->PolicyDocumentType, PolicyDocumentType::class);
        }

        return $this;
    }
}