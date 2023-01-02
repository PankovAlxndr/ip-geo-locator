<?php

namespace IpGeoLocator\Test;

use IpGeoLocator\Ip;
use PHPUnit\Framework\TestCase;

class IpTest extends TestCase
{
    public function testIP4()
    {
        $ip = new Ip('127.0.0.1');
        $this->assertSame('127.0.0.1', $ip->getValue());
    }

    public function testIP6()
    {
        $ip = new Ip('2001:0db8:85a3:0000:0000:8a2e:0370:7334');
        $this->assertSame('2001:0db8:85a3:0000:0000:8a2e:0370:7334', $ip->getValue());
    }

    public function testInvalidIp()
    {
        $this->expectException(\InvalidArgumentException::class);
        $ip = new Ip('awesome');
    }

    public function testNull()
    {
        $this->expectException(\ArgumentCountError::class);
        $ip = new Ip();
    }

    public function testEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $ip = new Ip('');
    }
}
