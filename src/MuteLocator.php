<?php

declare(strict_types=1);

namespace IpGeoLocator;

class MuteLocator implements LocatorInterface
{
    private LocatorInterface $next;
    private ErrorHandler $handler;

    public function __construct(LocatorInterface $next, ErrorHandler $handler)
    {
        $this->next = $next;
        $this->handler = $handler;
    }

    public function locate(Ip $ip): ?Location
    {
        try {
            return $this->next->locate($ip);
        } catch (\Exception $exception) {
            $this->handler->handle($exception);
            return null;
        }
    }
}
