<?php

namespace ReninsApi\Request\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;

/**
 * Driver
 *
 * @property int $IsIP
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
abstract class Driver extends Container
{
    protected static $rules = [
        'IsIP' => ['toLogical'],
        'FirstName' => ['toString', 'notEmpty'],
        'MiddleName' => ['toString', 'notEmpty'],
        'LastName' => ['toString', 'notEmpty'],
        'BirthDate' => ['toDate'],
        'Gender' => ['toString', 'in:M,F'],
        'MaritalStatus' => ['toInteger', 'between:1,4'],
        'HasChildren' => ['toLogical'],
        'DriveExperience' => ['toDate'],
        'Documents' => ['containerCollection'],
    ];

    public function toXml(\SimpleXMLElement $xml)
    {
        $this->validateThrow();

        $this->toXmlAttributes($xml, ['IsIP']);
        $this->toXmlTags($xml, ['FirstName', 'MiddleName', 'LastName', 'BirthDate', 'Gender', 'MaritalStatus', 'HasChildren', 'DriveExperience', 'Documents']);

        return $this;
    }


    protected $IsIP;
    protected $FirstName;
    protected $MiddleName;
    protected $LastName;
    protected $BirthDate;
    protected $Gender;
    protected $MaritalStatus;
    protected $HasChildren;
    protected $DriveExperience;
    protected $Documents;
}