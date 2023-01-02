<?php

namespace IpGeoLocator\Test;

use IpGeoLocator\Ip;
use IpGeoLocator\LocatorInterface;
use IpGeoLocator\MuteLocator;
use IpGeoLocator\PsrLogErrorHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class MuteLocatorTest extends TestCase
{
    public function testMute()
    {
        $mockLocator = $this->createMock(LocatorInterface::class);
        $mockLocator->method('locate')->willThrowException(new \Exception());

        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('error');

        $locator = new MuteLocator($mockLocator, new PsrLogErrorHandler($mockLogger));
        $actual = $locator->locate(new Ip('127.0.0.1'));

        $this->assertNull($actual);
    }
}
