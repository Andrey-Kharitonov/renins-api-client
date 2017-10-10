<?php

namespace ReninsApiTest\Request;

use PHPUnit\Framework\TestCase;
use ReninsApi\Request\ContainerCollection;
use ReninsApi\Request\ContainerCollectionException;
use ReninsApi\Response\Rest\Model;
use ReninsApi\Response\Rest\VehicleType;

class ContainerCollectionTest extends TestCase
{
    public function testDifferentItems()
    {
        $coll = new ContainerCollection();
        $coll->add(new Model());
        $coll->clear();
        $coll->add(new VehicleType());
        $this->expectException(ContainerCollectionException::class);
        $coll->add(new Model());
    }
}