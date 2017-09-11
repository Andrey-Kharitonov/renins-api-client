<?php

namespace ReninsApi\Request\Validator;

class Validator
{
    protected $rules;
    protected $errors;

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function validate(array $data) {
        $this->errors = [];

        foreach($this->rules as $field => $fieldRules) {
            $value = (array_key_exists($field, $data)) ? $data[$field] : null;
            if (!is_array($fieldRules)) {
                $fieldRules = explode(',', $fieldRules);
            }
            foreach($fieldRules as $fieldRule) {
                $fieldRule = trim($fieldRule);
                if ($fieldRule == '') continue;

                $params = null;
                $pos = mb_strrpos($fieldRule, ':');
                if ($pos !== false) {
                    $fieldRule = mb_substr($fieldRule, 0, $pos);
                    $params = mb_substr($fieldRule, $pos + 1);
                }

                $method = 'check' . ucfirst($fieldRule);
                if (!method_exists($this, $method)) {
                    throw new InvalidRuleException("Rule {$fieldRule} isn't supported");
                }

                $res = $this::{$method}($value, $params);
                if ($res !== true) {
                    $this->errors[$field][] = $res;
                }
            }
        }

        return count($this->errors) == 0;
    }

    /*
     * VALIDATION METHODS
     */

    public static function checkRequired($value, $params = null) {
        if ($value === null) {
            return 'Is required';
        }
        return true;
    }

    public static function checkLogical($value, $params = null) {
        return self::checkIn($value, 'YES,NO');
    }

    public static function checkIn($value, $params = null) {
        if ($value === null) return true;

        $paramsArr = explode('|', $params);
        $paramsArr = array_map(function($v) {return trim($v);}, $paramsArr);
        if (!in_array((string)$value, $paramsArr, true)) {
            $need = '';
            $cutted = false;
            foreach($paramsArr as $index => $param) {
                $need .= (($need != '') ? ', ' : '') . $param;
                if (mb_strlen($need) > 50) {
                    if ($index < count($paramsArr) - 1) {
                        //есть еще значения
                        $cutted = true;
                    }
                    break;
                }
            }

            return 'Invalid value. Allow ' . $need . (($cutted)?'...':'.');
        }
        return true;
    }

    public static function checkAmount($value, $params = null) {
        if ($value === null) return true;

        if (!is_numeric($value)) {
            return 'Invalid value. Allow valid number.';
        }
        return true;
    }

    public static function checkMin($value, $params = null) {
        if ($value === null) return true;

        if ($value < $params) {
            return 'Value is less than ' . $params;
        }
        return true;
    }

    public static function checkMax($value, $params = null) {
        if ($value === null) return true;

        if ($value > $params) {
            return 'Value is greater than ' . $params;
        }
        return true;
    }

    public static function checkBetween($value, $params = null) {
        if ($value === null) return true;

        $limits = explode(',', $params);
        if (count($limits) < 2) {
            throw new InvalidRuleException("Invalid parameters for rule \"between\"");
        }

        $res = self::checkMin($value, $limits[0]);
        if ($res !== true) {
            return $res;
        }
        $res = self::checkMax($value, $limits[1]);
        if ($res !== true) {
            return $res;
        }
        return true;
    }
}