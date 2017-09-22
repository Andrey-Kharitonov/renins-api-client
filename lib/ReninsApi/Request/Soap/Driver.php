<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Driver
 *
 * @property string $IsIP
 * @property string $FirstName
 * @property string $MiddleName
 * @property string $LastName
 * @property string $BirthDate
 * @property string $Gender
 * @property integer $MaritalStatus
 * @property string $HasChildren
 * @property string $DriveExperience
 * @property ContainerCollection $Documents
 */
class Driver extends Container
{
    protected $rules = [
        'IsIP' => ['toLogical'],
        'FirstName' => ['toString', 'notEmpty'],
        'MiddleName' => ['toString', 'notEmpty'],
        'LastName' => ['toString', 'notEmpty'],
        'BirthDate' => ['toString', 'date'],
        'Gender' => ['toString', 'in:M|F'],
        'MaritalStatus' => ['toInteger', 'between:1|4'],
        'HasChildren' => ['toLogical'],
        'DriveExperience' => ['toString', 'date'],
        'Documents' => ['containerCollection:' . Document::class],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->toXmlAttributes($xml, ['IsIP']);
        $this->toXmlTags($xml, ['FirstName', 'MiddleName', 'LastName', 'BirthDate', 'Gender', 'MaritalStatus', 'HasChildren', 'DriveExperience', 'Documents']);

        return $this;
    }
}