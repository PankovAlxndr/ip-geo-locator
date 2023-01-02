<?php

declare(strict_types=1);

namespace IpGeoLocator;

class Ip
{
    private string $value;

    public function __construct(string $ip)
    {
        if (empty($ip)) {
            throw new \InvalidArgumentException('Empty ip.');
        }
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            throw new \InvalidArgumentException('Invalid ip: ' . $ip);
        }

        $this->value = $ip;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
