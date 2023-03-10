<?php

declare(strict_types=1);

namespace IpGeoLocator;

class ChainLocator implements LocatorInterface
{
    /** @var LocatorInterface[] */
    private array $locators;

    /** @param array<int, LocatorInterface> $locators */
    public function __construct(...$locators)
    {
        $this->locators = $locators;
    }

    public function locate(Ip $ip): ?Location
    {
        foreach ($this->locators as $locator) {
            $location = $locator->locate($ip);
            if ($location !== null) {
                return $location;
            }
        }
        return null;
    }
}
