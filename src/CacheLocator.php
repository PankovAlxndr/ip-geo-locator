<?php

declare(strict_types=1);

namespace IpGeoLocator;

use Psr\SimpleCache\CacheInterface;

class CacheLocator implements LocatorInterface
{
    private LocatorInterface $next;
    private CacheInterface $cache;

    public function __construct(LocatorInterface $next, CacheInterface $cache)
    {
        $this->next = $next;
        $this->cache = $cache;
    }

    public function locate(Ip $ip): ?Location
    {
        // это уже не просто декоратор, а заместитель, так как мы перехватываем вызов
        // к оригинальному методу и что-то делаем, в данном случае оригинальный методы вообще
        // может не быть вызван
        $key = 'location-' . $ip->getValue();
        $location = $this->cache->get($key);

        if ($location === null) {
            $location = $this->next->locate($ip);
            $this->cache->set($key, $location);
        }

        return $location;
    }
}