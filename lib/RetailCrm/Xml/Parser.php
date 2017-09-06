<?php

namespace RetailCrm\Xml;

/**
 * Simple and fast xml parser via XMLReader.
 */
class Parser
{
    /**
     * @var callable
     */
    public $onOpenTag;

    /**
     * @var callable
     */
    public $onCloseTag;

    /**
     * @var callable
     */
    public $onText;

    /**
     * Parse xml and call user functions: function(XMLReader $reader, string $xpath)
     *   $reader - XMLReader
     *   $xpath - full path of the current element (for example: "/ArrayOfBrand/Brand/Name")
     * @param string $xml
     * @throws \Exception
     */
    public function parse(string $xml) {
        $reader = new \XMLReader();
        $reader->XML($xml);
        try {
            $xpath = '';
            while($reader->read()) {
                if ($reader->nodeType == \XMLReader::ELEMENT) {
                    $prevXpath = $xpath;
                    $xpath .= '/' . $reader->name;

                    //work starting of a tag here
                    if (is_callable($this->onOpenTag)) {
                        if (call_user_func($this->onOpenTag, $reader, $xpath) === false) {
                            break;
                        }
                    }
                    //---

                    if ($reader->isEmptyElement) {
                        $xpath = $prevXpath;
                    }
                } elseif ($reader->nodeType == \XMLReader::END_ELEMENT) {
                    $pos = strrpos($xpath, '/');
                    if ($pos === false || substr($xpath, $pos + 1) != $reader->name) {
                        throw new InvalidXmlException("Unexpected closing of tag {$reader->name} (xpath = {$xpath})");
                    }

                    //work closing of a tag here
                    if (is_callable($this->onCloseTag)) {
                        if (call_user_func($this->onCloseTag, $reader, $xpath) === false) {
                            break;
                        }
                    }
                    //---

                    $xpath = substr($xpath, 0, $pos);
                } elseif ($reader->nodeType == \XMLReader::TEXT) {

                    //work the text of a tag here
                    if (is_callable($this->onText)) {
                        if (call_user_func($this->onText, $reader, $xpath) === false) {
                            break;
                        }
                    }
                    //---
                }
            }
            $reader->close();
        } catch (\Exception $exc) {
            $reader->close();
            throw $exc;
        }
    }
}