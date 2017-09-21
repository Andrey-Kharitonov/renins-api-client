<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Risks
 *
 * @property string $PacketName
 * @property string $PacketCaption
 * @property string $CanBeChoosen
 * @property string $Visible
 * @property string $Enabled
 * @property ContainerCollection $Risk
 */
class Risks extends Container
{
    protected $rules = [
        'PacketName' => ['toString'], //unknown type
        'PacketCaption' => ['toString'], //unknown type
        'CanBeChoosen' => ['toString'], //unknown type
        'Visible' => ['toBoolean'],
        'Enabled' => ['toBoolean'],
        'Risk' => ['containerCollection:' . Risk::class],
    ];

    public function fromXml(\SimpleXMLElement $xml) {
        $this->fromXmlAttributes($xml, ['PacketName', 'PacketCaption', 'CanBeChoosen', 'Visible', 'Enabled']);

        $this->Risk = ContainerCollection::createFromXml($xml, Risk::class);

        return $this;
    }
}