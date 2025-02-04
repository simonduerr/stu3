<?php

class request
{

    public static function getvars()
    {
        global $_GET;
        return $_GET;
    }

    public static function postvars()
    {
        global $_POST;
        return $_POST;
    }

    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function has(string $key): bool
    {
        return self::getvars()[$key] ?? self::postvars()[$key] ?? false;
    }

    public static function getVarByMethod(&$method, &$var, $fatal = false)
    {
        if (!@array_key_exists($var, $method)) {
            if ($fatal === true) {
                new InvalidParamException($var);
            }
            return false;
        }
        return $method[$var];
    }

    public static function getInt($var, $std = 0)
    {
        $int = self::getVarByMethod(self::getvars(), $var);
        if (strlen($int) == 0) {
            return $std;
        }
        return self::returnInt($int);
    }

    public static function getIntFatal($var)
    {
        $int = self::getVarByMethod(self::getvars(), $var, true);
        return self::returnInt($int);
    }

    public static function postInt($var)
    {
        $int = self::getVarByMethod(self::postvars(), $var);
        return self::returnInt($int);
    }

    public static function postIntFatal($var)
    {
        $int = self::getVarByMethod(self::postvars(), $var, true);
        return self::returnInt($int);
    }

    public static function getString($var)
    {
        return self::getVarByMethod(self::getvars(), $var);
    }

    public static function postString($var)
    {
        return self::getVarByMethod(self::postvars(), $var);
    }

    public static function indString($var)
    {
        $value = self::getVarByMethod(self::postvars(), $var);
        if ($value) {
            return $value;
        }
        return self::getVarByMethod(self::getvars(), $var);
    }

    public static function indInt($var)
    {
        $value = self::getVarByMethod(self::postvars(), $var);
        if ($value) {
            return self::returnInt($value);
        }
        return self::returnInt(self::getVarByMethod(self::getvars(), $var));
    }

    public static function postStringFatal($var)
    {
        return self::getVarByMethod(self::postvars(), $var, true);
    }

    public static function getStringFatal($var)
    {
        return self::getVarByMethod(self::getvars(), $var, true);
    }

    public static function postArrayFatal($var)
    {
        return self::returnArray(self::getVarByMethod(self::postvars(), $var, true));
    }

    public static function postArray($var)
    {
        return self::returnArray(self::getVarByMethod(self::postvars(), $var));
    }

    public static function returnInt(&$result)
    {
        if (!$result || $result < 0) {
            return 0;
        }
        return intval($result);
    }

    public static function returnArray(&$result)
    {
        if (!is_array($result)) {
            return array();
        }
        return $result;
    }

    public static function setVar($var, $value)
    {
        global $_GET, $_POST;
        $_GET[$var] = $value;
        $_POST[$var] = $value;
    }

    public static function isAjaxRequest()
    {
        if (self::indInt('ajax') == 1) {
            return true;
        }
        return false;
    }

}
