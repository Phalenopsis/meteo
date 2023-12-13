<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
class FetchMeteo
{
    public function __construct(
        private HttpClientInterface $client, string $key
    )
    {
        $this->key = $key;
    }

    public function fetchTownCoords(string $town)
    {
        $response = $this->client->request(
            'GET',
            'http://api.openweathermap.org/geo/1.0/direct?q=' . $town . '&limit=1&appid=' . $this->key
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        //$content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        [$lat, $lon] = [$content[0]['lat'], $content[0]['lon']];

        return [$lat, $lon];
    } public function fetchMeteoAt(string $lat, string $lon)
    {

        $response = $this->client->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lon . '&appid='. $this->key . '&units=metric'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        //$content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]



        return $content;

    }

}