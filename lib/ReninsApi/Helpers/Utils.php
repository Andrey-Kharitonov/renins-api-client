<?php

namespace ReninsApi\Helpers;

use ReninsApi\Request\Container;

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

    /**
     * Add $from document to $to
     * @param \SimpleXMLElement $to
     * @param \SimpleXMLElement $from
     */
    public static function sxmlAppend(\SimpleXMLElement $to, \SimpleXMLElement $from) {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }
}