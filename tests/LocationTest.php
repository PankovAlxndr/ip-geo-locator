<?php

namespace IpGeoLocator\Test;

use IpGeoLocator\Location;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    private Location $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new Location('Россия', 'Ярославская', 'Ярославль');
    }

    public function testGetCountry()
    {
        $this->assertSame('Россия', $this->sut->getCountry());
    }

    public function testGetRegion()
    {
        $this->assertSame('Ярославская', $this->sut->getRegion());
    }

    public function testGetCity()
    {
        $this->assertSame('Ярославль', $this->sut->getCity());
    }
}
