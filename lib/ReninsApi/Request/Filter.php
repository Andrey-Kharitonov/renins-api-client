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

        if ($value === 'YES') {
            return true;
        } elseif ($value === 'NO') {
            return false;
        } else {
            return ($value) ? 'YES' : 'NO';
        }
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

    public function filterToDate($value, $params) {
        if ($value === null) return $value;

        if (is_string($value)) {
            $dt = \DateTime::createFromFormat('Y-m-d', $value);
            return ($dt) ? $dt->format('Y-m-d') : null;
        } elseif ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        } else {
            return null;
        }
    }

    public function filterToContainer($value, $className) {
        if ($value === null || $value instanceof Container) return $value;

        return new $className($value);
    }

    public function filterToContainerCollection($value, $className) {
        if ($value === null || $value instanceof Container) return $value;

        if (!is_array($value)) {
            throw new \InvalidArgumentException("Invalid type of value, array is expected");
        }

        $coll = new ContainerCollection();
        foreach ($value as $item) {
            $coll->add($item);
        }

        return $coll;
    }


}