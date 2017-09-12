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
            }

            foreach ($propRules as $propRule) {
                $propRule = trim($propRule);
                if ($propRule == '') continue;

                $method = 'filter' . ucfirst($propRule);
                if (!method_exists($this, $method)) continue;

                $data = $this::{$method}($data);
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

    public static function filterToLogical($value) {
        if ($value === null) return $value;

        return ($value) ? 'YES' : 'NO';
    }

    public static function filterToInteger($value) {
        if ($value === null) return $value;

        return (int) $value;
    }

    public static function filterToInt($value) {
        return static::filterToInteger($value);
    }

    public static function filterToString($value) {
        if ($value === null) return $value;

        return (string) $value;
    }

    public static function filterToDouble($value) {
        if ($value === null) return $value;

        return (double) $value;
    }
}