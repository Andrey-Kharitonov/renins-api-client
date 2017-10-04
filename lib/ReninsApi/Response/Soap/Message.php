<?php
namespace ReninsApi\Response\Soap;

use ReninsApi\Request\Container;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\Soap\Calculation\Deductible;

/**
 * Message info
 *
 * @property integer $code
 * @property string $level
 * @property string $text
 */
class Message extends Container
{
    protected $rules = [
        'code' => ['toInteger'],
        'level' => ['toString'],
        'text' => ['toString', 'required', 'notEmpty'],
    ];

    public function fromObject($obj) {
        $this->fromObjectOnly($obj, ['code', 'level']);

        if (!empty($obj->_)) {
            $this->text = $obj->_;
        }

        return $this;
    }
}