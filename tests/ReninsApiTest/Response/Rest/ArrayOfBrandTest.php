<?php

namespace ReninsApiTest\Response\Rest;

use PHPUnit\Framework\TestCase;
use ReninsApi\Helpers\Utils;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Response\Rest\ArrayOfBrand;
use ReninsApi\Response\Rest\Brand;

class ArrayOfBrandTest extends TestCase
{
    public function testImportFromXml()
    {
        $xml = @file_get_contents(Utils::pathCombine(DATA_DIR, 'VehicleBrandsAllWithModels_response.xml'));
        $resp = ArrayOfBrand::createFromXml($xml);
        $this->assertInstanceOf(ArrayOfBrand::class, $resp);
        $resp->validateThrow();
        $this->assertInstanceOf(ContainerCollection::class, $resp->Brand);
        $this->assertGreaterThan(10, $resp->Brand->count());

        /* @var Brand $brand */
        $brand = $resp->Brand->find('Name', 'ВАЗ');
        $this->assertInstanceOf(Brand::class, $brand);

        $this->assertInstanceOf(ContainerCollection::class, $brand->Models);
        $this->assertInstanceOf(ContainerCollection::class, $brand->VehicleTypes);

        $this->assertGreaterThan(10, $brand->Models->count());
        $this->assertEquals(1, $brand->VehicleTypes->count());
    }
}