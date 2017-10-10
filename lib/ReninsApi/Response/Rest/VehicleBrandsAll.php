<?php

namespace ReninsApi\Response\Rest;

use ReninsApi\Response\Rest;
use ReninsApi\Xml\Parser;

/**
 * Params for REST /Vehicle/Brands/All
 * @property $vehicleType string
 */
class VehicleBrandsAll extends Rest
{
    protected $brands = [];

    protected function parseXml() {
        $this->brands = [];

        $parser = new Parser();
        $parser->onText = function($reader, $xpath) {
            if ($xpath == '/ArrayOfBrand/Brand/Name') {
                $this->brands[] = $reader->value;
            }
        };
        $parser->parse($this->xml);
    }

    /**
     * Get array of brands
     * @return string[]
     */
    public function getBrands(): array {
        return $this->brands;
    }
}