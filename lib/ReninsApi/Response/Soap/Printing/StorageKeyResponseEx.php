<?php
namespace ReninsApi\Response\Soap\Printing;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Soap\Message;
use ReninsApi\Response\Soap\Error;

/**
 * Storage key info for PrintDocuments
 *
 * В случае Success = false ошибки собираются либо в Messages либо в Errors. Могут быть и там и там.
 * Необходимо проверять обе коллекции.
 *
 * В случае Success = true может быть заполнен Messages. Причем там могут быть ошибки о другом типе документа.
 * Например: если запросить типы 1 и 2. То будет один StorageKeyResponseEx с Success = true и с заполненным Messages.
 * И будет второй StorageKeyResponseEx с Success = true и с таким же Messages
 * См. комментарий после кода класса
 *
 * @property boolean $Success
 * @property ContainerCollection $Messages
 * @property StorageKeyResult $Result
 * @property ContainerCollection $Errors
 */
class StorageKeyResponseEx extends Container
{
    protected $rules = [
        'Success' => ['toBoolean', 'required'],
        'Messages' => ['containerCollection:' . Message::class],
        'Result' => ['container:' . StorageKeyResult::class],
        //ExtensionData
        'Errors' => ['containerCollection:' . Error::class],
    ];

    public function fromObject($obj) {
        $this->fromObjectOnly($obj, ['Success']);

        if (!empty($obj->Messages) && !empty($obj->Messages->Message)) {
            $this->Messages = ContainerCollection::createFromObject($obj->Messages->Message, Message::class);
        }

        if (!empty($obj->Result)) {
            $this->Result = StorageKeyResult::createFromObject($obj->Result);
        }

        if (!empty($obj->Errors) && !empty($obj->Errors->Error)) {
            $this->Errors = ContainerCollection::createFromObject($obj->Errors->Error, Error::class);
        }

        return $this;
    }
}

/*
Почему Messages дублируется?

Array
(
    [StorageKeyResponseEx] => Array
        (
            [0] => Array
                (
                    [Success] => 1
                    [Messages] => Array
                        (
                            [0] => Array
                                (
                                    [code] => 0
                                    [level] => Info
                                    [text] => Не найдено ни одного доступного документа типа: 2. Доступны следующие типы документов: 1, 7 (Метки: [NewCasco, HasStamp, HaveStamp], [NoHasStamp, NewCasco, NoStamp]).
                                )

                        )

                    [Result] => Array
                        (
                            [PdfPageCount] => 1
                            [PrintResultType] => Pdf
                            [TemporaryKey] => DOC_0102_37F3D9840B7E4B4C8E9721EE060DAB10_192168001095
                        )

                )

            [1] => Array
                (
                    [Success] =>
                    [Messages] => Array
                        (
                            [0] => Array
                                (
                                    [code] => 0
                                    [level] => Info
                                    [text] => Не найдено ни одного доступного документа типа: 2. Доступны следующие типы документов: 1, 7 (Метки: [NewCasco, HasStamp, HaveStamp], [NoHasStamp, NewCasco, NoStamp]).
                                )

                        )

                )

        )

)
*/