<?php

namespace IpGeoLocator\Test;

use IpGeoLocator\CacheLocator;
use IpGeoLocator\Ip;
use IpGeoLocator\Location;
use IpGeoLocator\LocatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class CacheLocatorTest extends TestCase
{
    public function testCache()
    {
        $expected = new Location('Россия');
        $mockLocator = $this->createMock(LocatorInterface::class);
        $mockCache = $this->createMock(CacheInterface::class);
        $mockCache->method('get')->willReturn($expected);
        $locator = new CacheLocator($mockLocator, $mockCache);
        $actual = $locator->locate(new Ip('127.0.0.1'));
        $this->assertNotNull($actual);
        $this->assertSame($expected, $actual);
    }

    public function testNotExistCache()
    {
        $expected = new Location('Россия');
        $mockLocator = $this->createMock(LocatorInterface::class);
        $mockLocator->method('locate')->willReturn($expected);
        $mockCache = $this->createMock(CacheInterface::class);
        $mockCache->method('get')->willReturn(null);
        $locator = new CacheLocator($mockLocator, $mockCache);
        $actual = $locator->locate(new Ip('127.0.0.1'));
        $this->assertNotNull($actual);
        $this->assertSame($expected, $actual);
    }

    public function testInvalidInstance()
    {
        $expected = new Location('Россия');
        $mockLocator = $this->createMock(LocatorInterface::class);
        $mockLocator->method('locate')->willReturn($expected);
        $mockCache = $this->createMock(CacheInterface::class);
        $mockCache->method('get')->willReturn('scalar type');
        $locator = new CacheLocator($mockLocator, $mockCache);
        $actual = $locator->locate(new Ip('127.0.0.1'));
        $this->assertNull($actual);
    }
}
