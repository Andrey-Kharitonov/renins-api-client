<?php
namespace ReninsApi\Response\Soap\Calculation;

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
    protected $rules = [
        'program_name' => ['toString'], //unknown type
        'program_guid' => ['toString'], //unknown type
        'expired_date' => ['toString'], //unknown type
        'printToken' => ['toString'],
        'CalcResults' => ['containerCollection:' . CalcResults::class],
    ];

    public function fromXml(\SimpleXMLElement $xml) {
        $this->fromXmlAttributes($xml, ['program_name', 'program_guid', 'expired_date', 'printToken']);

        $this->CalcResults = ContainerCollection::createFromXml($xml->CalcResults, CalcResults::class);

        return $this;
    }

    /**
     * Is successful response
     * @return bool
     */
    public function isSuccessful() {
        if (!$this->CalcResults || !$this->CalcResults->count()) {
            return true;
        }

        /*
         * If there is successful CalcResults, it will mean successful response
         */
        $ret = false;
        foreach($this->CalcResults as $calcResults) {
            /* @var CalcResults $calcResults */
            if ($calcResults->Success == 'true') {
                $ret = true;
                break;
            }
        }

        return $ret;
    }

}