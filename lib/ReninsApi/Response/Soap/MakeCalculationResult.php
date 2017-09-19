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
        'program_name' => ['toString'],
        'program_guid' => ['toString'],
        'expired_date' => ['toString'],
        'printToken' => ['toString'],
        'CalcResults' => ['containerCollection'],
    ];

    protected $program_name;
    protected $program_guid;
    protected $expired_date;
    protected $printToken;
    protected $CalcResults;

    public function fromXml(\SimpleXMLElement $xml) {
        foreach($xml->attributes() as $name => $value) {
            $name = (string) $name;
            $value = (string) $value;
            if (isset($this->_publicProperties[$name])) {
                //public property will be set directly
                $this->{$name} = $value;
            } else {
                $this->__set($name, $value);
            }
        }

        foreach($xml->children() as $child) {
            $name = $child->getName();
            $value = (string) $child;
            if (isset($this->_publicProperties[$name])) {
                //public property will be set directly
                $this->{$name} = $value;
            } else {
                $this->__set($name, $value);
            }
        }

        return $this;
    }

}