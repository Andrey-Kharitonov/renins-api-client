<?php

namespace ReninsApi\Request;

class Filter
{
    protected $rules;

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    public function filter($data, $property = null) {
        if ($property !== null) {
            //one property

            if (!isset($this->rules[$property])) {
                //no rule
                return $data;
            }

            $propRules = $this->rules[$property];
            if (!is_array($propRules)) {
                $propRules = explode(',', $propRules);
                $propRules = array_map(function($v) {
                    return trim($v);
                }, $propRules);
            }

            foreach ($propRules as $propRule) {
                if ($propRule == '' || substr($propRule, 0, 2) != 'to') continue;

                $params = null;
                $pos = mb_strpos($propRule, ':');
                if ($pos !== false) {
                    $params = mb_substr($propRule, $pos + 1);
                    $propRule = mb_substr($propRule, 0, $pos);
                }

                $method = 'filter' . ucfirst($propRule);
                if (!method_exists($this, $method)) {
                    throw new FilterException("Rule {$propRule} isn't supported");
                }

                $data = $this::{$method}($data, $params);
            }

            return $data;
        } else {
            //all properties

            if (!is_array($data)) {
                throw new \InvalidArgumentException("Data must be array");
            }

            $ret = [];

            foreach($data as $prop => $value) {
                $ret[$prop] = $this->filter($value, $prop);
            }

            return $ret;
        }
    }

    public static function filterToLogical($value, $params) {
        if ($value === null) return $value;

        if (strcasecmp($value, 'YES') == 0) {
            return 'YES';
        } elseif (strcasecmp($value, 'NO') == 0) {
            return 'NO';
        } else {
            return ($value) ? 'YES' : 'NO';
        }
    }

    public static function filterToBooleanStr($value, $params) {
        if ($value === null) return $value;

        if (strcasecmp($value, 'true') == 0) {
            return 'true';
        } elseif (strcasecmp($value, 'false') == 0) {
            return 'false';
        } else {
            return ($value) ? 'true' : 'false';
        }
    }

    public static function filterToYN($value, $params) {
        if ($value === null) return $value;

        if (strcasecmp($value, 'Y') == 0) {
            return 'Y';
        } elseif (strcasecmp($value, 'N') == 0) {
            return 'N';
        } else {
            return ($value) ? 'Y' : 'N';
        }
    }

    public static function filterToBoolean($value, $params) {
        if ($value === null) return $value;
        return (bool) $value;
    }

    public static function filterToInteger($value, $params) {
        if ($value === null) return $value;

        return (int) $value;
    }

    public static function filterToInt($value, $params) {
        return static::filterToInteger($value);
    }

    public static function filterToString($value, $params) {
        if ($value === null) return $value;

        return (string) $value;
    }

    public static function filterToDouble($value, $params) {
        if ($value === null) return $value;

        return (double) $value;
    }
}