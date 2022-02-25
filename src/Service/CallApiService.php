<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDepartement(): array
    {
        $apiUrl = 'https://geo.api.gouv.fr/departements?fields=nom,code,codeRegion';

        $reponse = $this->client->request(
            'GET',
            $apiUrl
        );

        return $reponse->toArray();
    }

    public function getCommuneByDepartement(string $departement): array
    {

        $apiUrl = 'https://geo.api.gouv.fr/departements/' . $departement .  '/communes?fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre';

        $reponse = $this->client->request(
            'GET',
            $apiUrl
        );

        return $reponse->toArray();
    }

    public function getCommuneNameByDepartement(string $departement): array
    {
        $apiUrl = 'https://geo.api.gouv.fr/departements/' . $departement .  '/communes?fields=nom,codesPostaux&format=json&geometry=centre';

        $reponse = $this->client->request(
            'GET',
            $apiUrl
        );

        return $reponse->toArray();
    }


    public function getAirports(): array
    {
        $apiUrl = "https://zeugy:apipw243163@opensky-network.org/api/states/all";
        $reponse = $this->client->request(
            'GET',
            $apiUrl
        );

        return $reponse->toArray();
    }

    public function getArrivalbyAirportCode(string $code): array
    {

        $apiUrl = "https://zeugy:apipw243163@opensky-network.org/api/flights/departure?airport=" . $code . "&begin=1517227200&end=1517230800";
        $reponse = $this->client->request(
            'GET',
            $apiUrl
        );

        return $reponse->toArray();
    }
}
