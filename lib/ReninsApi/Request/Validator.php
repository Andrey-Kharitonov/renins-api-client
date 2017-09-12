<?php

namespace ReninsApi\Request;

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

    public function validate(array $data)
    {
        $this->errors = [];

        foreach ($this->rules as $property => $propRules) {
            $value = (array_key_exists($property, $data)) ? $data[$property] : null;

            if (!is_array($propRules)) {
                $propRules = explode(',', $propRules);
            }
            foreach ($propRules as $propRule) {
                $propRule = trim($propRule);
                if ($propRule == '') continue;

                $params = null;
                $pos = mb_strrpos($propRule, ':');
                if ($pos !== false) {
                    $propRule = mb_substr($propRule, 0, $pos);
                    $params = mb_substr($propRule, $pos + 1);
                }

                $method = 'check' . ucfirst($propRule);
                if (!method_exists($this, $method)) continue;

                $res = $this::{$method}($value, $params);
                if ($res !== true) {
                    $this->errors[$property][] = $res;
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

    public static function checkNotEmpty($value, $params = null) {
        if ($value === null) return true;

        if (is_string($value)) {
            //"0" isn't empty
            if ($value === '') {
                return 'Is empty';
            }
        } elseif($value instanceof ContainerCollection) {
            if ($value->count() <= 0) {
                return 'Is empty';
            }
        } elseif (empty($value)) {
            return 'Is empty';
        }
        return true;
    }

    public static function checkLogical($value, $params = null)
    {
        return static::checkIn($value, 'YES,NO');
    }

    public static function checkIn($value, $params = null)
    {
        if ($value === null) return true;

        $paramsArr = explode('|', $params);
        $paramsArr = array_map(function ($v) {
            return trim($v);
        }, $paramsArr);
        if (!in_array((string)$value, $paramsArr, true)) {
            $need = '';
            $cutted = false;
            foreach ($paramsArr as $index => $param) {
                $need .= (($need != '') ? ', ' : '') . $param;
                if (mb_strlen($need) > 50) {
                    if ($index < count($paramsArr) - 1) {
                        //есть еще значения
                        $cutted = true;
                    }
                    break;
                }
            }

            return 'Invalid value. Allow ' . $need . (($cutted) ? '...' : '.');
        }
        return true;
    }

    public static function checkSum($value, $params = null)
    {
        if ($value === null) return true;

        if (!is_double($value)) {
            return 'Invalid value. Allow double number.';
        }
        return true;
    }

    public static function checkMin($value, $params = null)
    {
        if ($value === null) return true;

        if ($value < $params) {
            return 'Value is less than ' . $params;
        }
        return true;
    }

    public static function checkMax($value, $params = null)
    {
        if ($value === null) return true;

        if ($value > $params) {
            return 'Value is greater than ' . $params;
        }
        return true;
    }

    public static function checkBetween($value, $params = null)
    {
        if ($value === null) return true;

        $limits = explode(',', $params);
        if (count($limits) < 2) {
            throw new ValidatorException("Invalid parameters for rule \"between\"");
        }

        $res = static::checkMin($value, $limits[0]);
        if ($res !== true) {
            return $res;
        }
        $res = static::checkMax($value, $limits[1]);
        if ($res !== true) {
            return $res;
        }
        return true;
    }

    public static function checkContainer($value, $params = null)
    {
        if ($value === null) return true;

        if ($value instanceof Container) {
            return "Isn't container";
        }
        return true;
    }

    public static function checkContainerCollection($value, $params = null)
    {
        if ($value === null) return true;

        if ($value instanceof ContainerCollection) {
            return "Isn't container collection";
        }
        return true;
    }

    public static function checkDate($value, $params = null) {
        if ($value === null) return true;

        $dt = \DateTime::createFromFormat('Y-m-d', $value);
        if (!$dt || $dt->format('Y-m-d') !== $value) {
            return "Isn't correct date";
        }
        return true;
    }
}