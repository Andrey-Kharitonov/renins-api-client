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
                $propRules = array_map(function($v) {
                    return trim($v);
                }, $propRules);
            }
            foreach ($propRules as $propRule) {
                if ($propRule == '' || substr($propRule, 0, 2) == 'to') continue;

                $params = null;
                $pos = mb_strpos($propRule, ':');
                if ($pos !== false) {
                    $params = mb_substr($propRule, $pos + 1);
                    $propRule = mb_substr($propRule, 0, $pos);
                }

                $method = 'check' . ucfirst($propRule);
                if (!method_exists($this, $method)) {
                    throw new ValidatorException("Rule {$propRule} isn't supported");
                }

                try {
                    $res = $this::{$method}($value, $params);
                    if ($res !== true) {
                        $this->errors[$property][] = $res;
                    }
                } catch (ValidatorException $exc) {
                    throw new ValidatorException($property . ': ' . $exc->getMessage(), 0, $exc);
                }
            }
        }

        return count($this->errors) == 0;
    }

    /*
     * VALIDATION METHODS
     */

    /**
     * Will return error, if $value === null
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkRequired($value, $params = null)
    {
        if ($value === null) {
            return 'Is required';
        }
        return true;
    }

    /**
     * Will return error, if it is empty container, empty string (except "0") or empty($value)
     * It will pass null! Check null by rule 'required'
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkNotEmpty($value, $params = null)
    {
        if ($value === null) return true;

        if (is_string($value)) {
            //"0" isn't empty
            if ($value === '') {
                return 'Is empty';
            }
        } elseif ($value instanceof ContainerCollection) {
            if ($value->count() <= 0) {
                return 'Is empty';
            }
        } elseif (empty($value)) {
            return 'Is empty';
        }
        return true;
    }

    /**
     * Will return error, if it isn't "YES" or "NO"
     * It will pass null.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkLogical($value, $params = null)
    {
        return static::checkIn($value, 'YES|NO');
    }

    /**
     * Will return error, if it isn't included in $params
     * It will pass null.
     * @param $value
     * @param $params - string "value1|value2|value3"
     * @return bool|string
     */
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

    /**
     * Will return error, if it isn't double type
     * It will pass null.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkSum($value, $params = null)
    {
        if ($value === null) return true;

        if (!is_double($value)) {
            return 'Invalid value. Allow double number.';
        }
        return true;
    }

    /**
     * Will return error, if $value < $params
     * It will pass null.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkMin($value, $params = null)
    {
        if ($value === null) return true;

        if ($value < $params) {
            return 'Value is less than ' . $params;
        }
        return true;
    }

    /**
     * Will return error, if $value > $params
     * It will pass null.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkMax($value, $params = null)
    {
        if ($value === null) return true;

        if ($value > $params) {
            return 'Value is greater than ' . $params;
        }
        return true;
    }

    /**
     * Will return error, if $value < min or $value > max
     * It will pass null.
     * @param $value
     * @param $params - string "min|max"
     * @return bool|string
     */
    public static function checkBetween($value, $params = null)
    {
        if ($value === null) return true;

        $limits = explode('|', $params);
        if (count($limits) < 2) {
            throw new ValidatorException("Invalid parameters for rule \"between\" ({$params})");
        }
        $limits = array_map(function($v) {
            return trim($v);
        }, $limits);

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

    /**
     * Will return error, if $value isn't instance of $className
     * It will pass null.
     * @param $value
     * @param $className
     * @return bool|string
     */
    public static function checkContainer($value, $className = null)
    {
        if (!$className) {
            throw new ValidatorException("Parameter className is required for rule container");
        }

        if ($value === null) return true;

        if (!($value instanceof $className)) {
            return "Isn't {$className}";
        }

        return true;
    }

    /**
     * Will return error, if $value isn't instance of ContainerCollection or collection contains containers of class different $className
     * It will pass null.
     * @param $value
     * @param $className
     * @return bool|string
     */
    public static function checkContainerCollection($value, $className = null)
    {
        if (!$className) {
            throw new ValidatorException("Parameter className is required for rule containerCollection");
        }

        if ($value === null) return true;

        if (!($value instanceof ContainerCollection)) {
            return "Isn't container collection";
        }
        if ($value->count() > 0 && !($value->get(0) instanceof $className)) {
            return "Isn't collection of {$className}";
        }

        return true;
    }

    /**
     * It should be string "Y-m-d".
     * Null will be passed.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkDate($value, $params = null)
    {
        if ($value === null) return true;

        $dt = \DateTime::createFromFormat('Y-m-d', $value);
        if (!$dt || $dt->format('Y-m-d') !== $value) {
            return "Isn't correct date. Allow Y-m-d.";
        }
        return true;
    }

    /**
     * It should be string "Y-m-d".
     * Null will be passed.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkDateTime($value, $params = null)
    {
        if ($value === null) return true;

        $dt = new \DateTime($value);
        if ($dt->format('Y-m-d') . 'T' . $dt->format('H:i:s') !== $value
            && $dt->format('Y-m-d H:i:s') != $value) {
            return "Isn't correct date time. Allow Y-m-dTH:i:s or Y-m-d H:i:s.";
        }
        return true;
    }

    public static function checkParticipantType($value, $params = null)
    {
        return self::checkIn($value, '1|2');
    }

    public static function checkVehicleType($value, $params = null)
    {
        if ($params == 'import') {
            return self::checkIn($value, 'Легковое ТС|Грузовое ТС|Автобус|Микроавтобус|Спецтехника|Малотоннажное ТС|Троллейбус|Трамвай|Мотоцикл|Прицепы к легковым автомобилям|Прицепы к грузовым автомобилям|Прицепы к тракторам и спецтехнике');
        } else {
            return self::checkIn($value, 'Легковое ТС|Грузовое ТС|Автобус|Микроавтобус|Спецтехника|Малотоннажное ТС|Троллейбус|Трамвай|Мотоцикл');
        }
    }

    /**
     * Тип документа для импорта
     * @param $value
     * @param $params - "import" - для импорта иначе для расчета
     * @return bool|string
     */
    public static function checkDocType($value, $params = null)
    {
        if ($params == 'import') {
            return self::checkIn($value, 'PASSPORT|DRIVING_LICENCE|ZAGRAN_PASSPORT|FOREIGN_PASSPORT|MILITARY_CARD'
                . '|REGISTRATION_CERTIFICATE|RESIDENTIAL_PERMIT|SOLDIER_IDENTIFY_CARD|PTS|STS|PSM|DIAGNOSTIC_CARD|TALON_TECHOSMOTR');
        } else {
            return self::checkIn($value, 'Паспорт РФ|PASSPORT|DRIVING_LICENCE|ZAGRAN_PASSPORT|FOREIGN_PASSPORT|MILITARY_CARD'
                . '|REGISTRATION_CERTIFICATE|RESIDENTIAL_PERMIT|SOLDIER_IDENTIFY_CARD|PTS|DIAGNOSTIC_CARD|TALON_TECHOSMOTR|STS');
        }
    }

    /**
     * Will return error, if $value have length < min or > max
     * It will pass null.
     * @param $value
     * @param $params - Examples: "min|max", "|max", "min|"
     * @return bool|string
     */
    public static function checkLength($value, $params = null)
    {
        if ($value === null) return true;

        $limits = explode('|', $params);
        $limits = array_map(function($v) {
            return trim($v);
        }, $limits);

        if (is_array($value)) {
            if ($limits[0] != '' && count($value) < $limits[0]) {
                return 'Length of array is less than ' . $limits[0];
            }
            if (isset($limits[1]) && $limits[1] != '' && count($value) > $limits[1]) {
                return 'Length of array is greater than ' . $limits[1];
            }
        } elseif ($value instanceof ContainerCollection) {
            if ($limits[0] != '' && $value->count() < $limits[0]) {
                return 'Length of collection is less than ' . $limits[0];
            }
            if (isset($limits[1]) && $limits[1] != '' && $value->count() > $limits[1]) {
                return 'Length of collection is greater than ' . $limits[1];
            }
        } else {
            if ($limits[0] != '' && strlen($value) < $limits[0]) {
                return 'Length of string is less than ' . $limits[0];
            }
            if (isset($limits[1]) && $limits[1] != '' && strlen($value) > $limits[1]) {
                return 'Length of string is greater than ' . $limits[1];
            }
        }

        return true;
    }

    /**
     * It should be array. Each item of array will be checked by rule.
     * Cascade calls are supported. It's possible: "array:array:between:1|8".
     * It will pass null.
     * @param $value
     * @param $rule
     * @return bool|string
     */
    public static function checkArray($value, $rule = null) {
        if ($value === null) return true;

        if (!is_array($value)) {
            return "Isn't array";
        }

        if ($rule != '') {
            $params = null;
            $pos = mb_strpos($rule, ':');
            if ($pos !== false) {
                $params = mb_substr($rule, $pos + 1);
                $rule = mb_substr($rule, 0, $pos);
            }

            $method = 'check' . ucfirst($rule);
            if (!method_exists(static::class, $method)) {
                throw new ValidatorException("Rule {$rule} isn't supported");
            }

            foreach($value as $index => $item) {
                $res = static::{$method}($item, $params);
                if ($res !== true) {
                    return "{$index}: {$res}";
                }
            }
        }

        return true;
    }

    /**
     * It should be string.
     * Null will be passed.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkString($value, $params = null) {
        if ($value === null) return true;

        if (!is_string($value)) {
            echo var_dump($value);
            return "Isn't string";
        }

        return true;
    }

    /**
     * enumPersonType
     * Тип персоны/лица/участника договора.
     * Физическое лицо / Юридическое лицо.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkPersonType($value, $params = null)
    {
        return self::checkIn($value, 'CONTACT|ACCOUNT');
    }

    /**
     * Value should be valid time (H:i:s)
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkTime($value, $params = null) {
        if ($value === null) return true;

        $matches = [];
        if (!preg_match("/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $value, $matches)) {
            return "Isn't valid time";
        }

        if ($matches[1] > 23 || $matches[2] > 59 || $matches[3] > 59) {
            return "Isn't valid time";
        }

        return true;
    }

    /**
     * Value should be valid currency code
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkCurrency($value, $params = null)
    {
        return self::checkIn($value, 'RUR|USD|EUR|GBP');
    }

    /**
     * Enumerator. Transmission type.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkTransmissionType($value, $params = null)
    {
        return self::checkIn($value, 'Автоматическая|Механическая|АКПП|МКПП|Вариатор|Роботизированная КП');
    }

    /**
     * Enumerator. Engine type.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkEngineType($value, $params = null)
    {
        return self::checkIn($value, 'Бензиновый|Дизельный|Гибрид');
    }

    /**
     * Enumerator. Vehicle body type.
     * @param $value
     * @param $params
     * @return bool|string
     */
    public static function checkVehicleBodyType($value, $params = null)
    {
        return self::checkIn($value, 'Седан|Хэтчбек|Универсал|Минивэн|Кабриолет|Пикап|Фургон|Купе|Лимузин|Внедорожник|Иное');
    }

}