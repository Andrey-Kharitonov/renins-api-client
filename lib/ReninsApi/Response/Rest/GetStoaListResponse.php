<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Ответ справочника СТОА для ремонта ОСАГО
 *
 * @property string $Success
 * @property string $ErrorMessage
 * @property ContainerCollection $Stoa
 */
class GetStoaListResponse extends Container
{
    protected $rules = [
        'Success' => ['toBooleanStr', 'required'],
        'ErrorMessage' => ['toString'],
        'Stoa' => ['containerCollection:' . StoaItem::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Success = (string) $xml->Success;
        $this->ErrorMessage = (string) $xml->ErrorMessage;
        if ($xml->Stoa[0]) {
            $this->Stoa = ContainerCollection::createFromXml($xml->Stoa[0]->StoaItem, StoaItem::class);
        }
        return $this;
    }
}