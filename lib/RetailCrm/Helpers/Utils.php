<?php

namespace RetailCrm\Helpers;

class Utils
{
    /**
     * Join path parts via '/'
     * @param string
     * @return string
     */
    public static function pathCombine() {
        $ret = "";
        $argCount = func_num_args();
        for($i = 0; $i < $argCount; $i++) {
            $arg = func_get_arg($i);
            if ($arg == '') {
                continue;
            }
            $ret .= (( $ret && substr($ret, -1) != '/' ) ? '/': '') . $arg;
        }
        return $ret;
    }
}