<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Risk
 *
 * @property string $Name
 * @property string $Bonus
 * @property string $Sum
 * @property string $TakeIntoRate
 * @property ContainerCollection $Coefs
 */
class Risk extends Container
{
    protected $rules = [
        'Name' => ['toString'],
        'Bonus' => ['toString'], //unknown type
        'Sum' => ['toString'], //unknown type
        'TakeIntoRate' => ['toString'],
        'Coefs' => ['containerCollection:' . Coef::class],
    ];

    public function fromXml(\SimpleXMLElement $xml) {
        $this->fromXmlAttributes($xml, ['Name', 'Bonus', 'Sum', 'TakeIntoRate']);

        if ($xml->Coefs) {
            $this->Coefs = ContainerCollection::createFromXml($xml->Coefs[0], Coef::class);
        }

        return $this;
    }
}