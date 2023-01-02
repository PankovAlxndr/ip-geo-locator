<?php

namespace IpGeoLocator\Test;

use IpGeoLocator\ChainLocator;
use IpGeoLocator\Ip;
use IpGeoLocator\Location;
use IpGeoLocator\LocatorInterface;
use PHPUnit\Framework\TestCase;

class ChainLocatorTest extends TestCase
{
    public function testSuccess()
    {
        $expected = new Location('Россия');
        $locators = [
            $this->mockLocator(null),
            $this->mockLocator($expected),
            $this->mockLocator(new Location('Япония')),
        ];
        $locator = new ChainLocator(...$locators);
        $actual = $locator->locate(new Ip('127.0.0.1'));
        $this->assertNotNull($actual);
        $this->assertSame($expected, $actual);
    }

    public function testNull()
    {
        $locators = [
            $this->mockLocator(null),
            $this->mockLocator(null),
            $this->mockLocator(null),
        ];
        $locator = new ChainLocator(...$locators);
        $actual = $locator->locate(new Ip('127.0.0.1'));
        $this->assertNull($actual);
    }

    private function mockLocator(?Location $location): LocatorInterface
    {
        $mock = $this->createMock(LocatorInterface::class);
        $mock->method('locate')->willReturn($location);
        return $mock;
    }
}
