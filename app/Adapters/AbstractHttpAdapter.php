<?php

namespace App\Adapters;

use GuzzleHttp\Client as HttpClient;

abstract class AbstractHttpAdapter
{
    protected HttpClient $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    abstract protected function endpoint(): string;
    abstract protected function methodHTTP(): string;

    public function execute(array $options): array
    {
        return $this->sendRequest($options);
    }

    protected function sendRequest(array $data): array
    {
        $responseJson = json_decode($this->httpClient->request(
            $this->methodHTTP(),
            $this->endpoint(),
            $data
        )->getBody());

        return collect($responseJson)->toArray();
    }
}
