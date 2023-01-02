<?php

declare(strict_types=1);

use IpGeoLocator\Ip;
use IpGeoLocator\Location;
use IpGeoLocator\LocatorInterface;

class DaDataLocator implements LocatorInterface
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function locate(Ip $ip): ?Location
    {
        // в конструктор класса можно передать PSR-18 клиент и делать "работу" через него, а не через curl

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=' . $ip->getValue());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: Token ' . $this->apiKey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception($error);
        }
        curl_close($ch);
        $result = json_decode($result, true);

        return new Location(
            $result['location']['data']['country'],
            $result['location']['data']['region'],
            $result['location']['data']['city']
        );

    }
}