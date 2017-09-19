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

        if (strcasecmp($value, 'YES') == 0) {
            return 'YES';
        } elseif (strcasecmp($value, 'NO') == 0) {
            return 'NO';
        } else {
            return ($value) ? 'YES' : 'NO';
        }
    }

    public static function filterToBoolean($value, $params) {
        if ($value === null) return $value;

        if (strcasecmp($value, 'true') == 0) {
            return 'true';
        } elseif (strcasecmp($value, 'false') == 0) {
            return 'false';
        } else {
            return ($value) ? 'true' : 'false';
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

    /*
    public function filterToContainer($value, $className) {
        if (!$className) {
            throw new FilterException("Parameter className is required for rule toContainer");
        }

        if ($value === null) return $value;

        if ($value instanceof Container) {
            if (!($value instanceof $className)) {
                throw new \InvalidArgumentException("Invalid type of value. It's Container, but isn't {$className}");
            }
            return $value;
        }

        if (!is_array($value)) {
            throw new \InvalidArgumentException("Invalid type of value, array is expected");
        }

        return new $className($value);
    }

    public function filterToContainerCollection($value, $className) {
        if (!$className) {
            throw new FilterException("Parameter className is required for rule toContainerCollection");
        }

        if ($value === null) return $value;
        if ($value instanceof ContainerCollection) return $value;

        if (!is_array($value)) {
            throw new \InvalidArgumentException("Invalid type of value, array is expected");
        }

        $coll = new ContainerCollection();
        foreach ($value as $item) {
            $coll->add(new $className($item));
        }

        return $coll;
    }
    */


}