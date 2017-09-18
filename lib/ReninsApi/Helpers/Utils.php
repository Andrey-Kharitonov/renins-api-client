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

    /**
     * Convert SimpleXMLElement to string without <?xml>
     * @param \SimpleXMLElement $sxml
     * @return string
     */
    public static function sxmlToStr(\SimpleXMLElement $sxml) {
        $domxml = dom_import_simplexml($sxml);
        return $domxml->ownerDocument->saveXML($domxml->ownerDocument->documentElement);
    }

    /**
     * Gen Uuid
     * @return string
     */
    public static function genUuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}