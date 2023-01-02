<?php

declare(strict_types=1);

namespace IpGeoLocator;

interface LocatorInterface
{
    public function locate(Ip $ip): ?Location;
}
