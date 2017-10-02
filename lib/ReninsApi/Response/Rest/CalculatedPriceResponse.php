<?php
namespace ReninsApi\Response\Rest;

use ReninsApi\Request\Container;

/**
 * Ответ справочника вилок стоимости ТС
 *
 * @property string $Success
 * @property string $Error
 * @property DiapasonPrice $Price
 */
class CalculatedPriceResponse extends Container
{
    protected $rules = [
        'Success' => ['toBooleanStr', 'required'],
        'Error' => ['toString'],
        'Price' => ['container:' . DiapasonPrice::class],
    ];

    public function fromXml(\SimpleXMLElement $xml)
    {
        $this->Success = (string) $xml->Success;
        $this->Error = (string) $xml->Error;
        if ($xml->Price[0]) {
            $this->Price = DiapasonPrice::createFromXml($xml->Price[0]);
        }
        return $this;
    }
}