<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Calc answer
 *
 * @property string $program_name
 * @property string $program_guid
 * @property string $expired_date
 * @property string $printToken
 * @property ContainerCollection $CalcResults
 *
 */
class MakeCalculationResult extends Container
{
    protected static $rules = [
        'program_name' => ['toString'], //unknown type
        'program_guid' => ['toString'], //unknown type
        'expired_date' => ['toString'], //unknown type
        'printToken' => ['toString'],
        'CalcResults' => ['containerCollection'],
    ];

    public function fromXml(\SimpleXMLElement $xml) {
        $this->fromXmlAttributes($xml, ['program_name', 'program_guid', 'expired_date', 'printToken']);

        if ($xml) {
            $coll = new ContainerCollection();
            $coll->fromXml($xml, CalcResults::class);
            $this->set('CalcResults', $coll);
        }

        return $this;
    }

}