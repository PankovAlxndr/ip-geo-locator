<?php

namespace IpGeoLocator;

interface ErrorHandler
{
    public function handle(\Exception $exception): void;
}
