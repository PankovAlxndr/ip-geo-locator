<?php

declare(strict_types=1);

namespace IpGeoLocator;

use Psr\SimpleCache\CacheInterface;

class CacheLocator implements LocatorInterface
{
    private LocatorInterface $next;
    private CacheInterface $cache;
    private string $cachePrefix;

    public function __construct(LocatorInterface $next, CacheInterface $cache, string $cachePrefix = 'location-')
    {
        $this->next = $next;
        $this->cache = $cache;
        $this->cachePrefix = $cachePrefix;
    }

    public function locate(Ip $ip): ?Location
    {
        // это уже не просто декоратор, а заместитель, так как мы перехватываем вызов
        // к оригинальному методу и что-то делаем, в данном случае оригинальный метод вообще
        // может не быть вызван
        $key = $this->cachePrefix . $ip->getValue();
        /** @psalm-var mixed $location */
        $location = $this->cache->get($key);
        if ($location === null) {
            $location = $this->next->locate($ip);
            $this->cache->set($key, $location);
            return $location;
        } elseif ($location instanceof Location) {
            return $location;
        }
        return null;
    }
}
