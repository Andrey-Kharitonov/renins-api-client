<?php

namespace ReninsApi\Response\Rest;

use ReninsApi\Response\Rest;
use ReninsApi\Xml\Parser;

/**
 * Params for REST /Vehicle/Brands/All and /Vehicle/Brands/AllWithModels
 * @property $vehicleType string
 */
class VehicleBrandsAll extends Rest
{
    /**
     * @var array
     */
    protected $brands = [];

    protected function parseXml() {
        $this->brands = [];

        $sxml = new \SimpleXMLElement($this->xml);
        foreach($sxml->Brand as $brand) {
            $types = [];
            if ($brand->VehicleTypes) {
                foreach ($brand->VehicleTypes->Classifier as $classifier) {
                    $types[] = (string) $classifier->Name;
                }
            }

            $models = [];
            if ($brand->Models) {
                foreach($brand->Models->Model as $model) {
                    $models[] = (string) $model->Name;
                }
            }

            $this->brands[] = ['Name' => (string) $brand->Name, 'VehicleTypes' => $types, 'Models' => $models];
        }
    }

    /**
     * Get array of brands
     *
     * Example:
     * [
     *  ['Name' => 'Acura', 'VehicleTypes' => ['Легковое ТС'], 'Models' => ['MDX', 'RDX']],
     *  ['Name' => 'Alfa Romeo', 'VehicleTypes' => ['Легковое ТС']],
     *  ['Name' => 'Aston Martin', 'VehicleTypes' => ['Легковое ТС']]
     * ]
     *
     * For /Vehicle/Brands/All 'Models' will be empty
     *
     * @return string[]
     */
    public function getBrands(): array {
        return $this->brands;
    }
}