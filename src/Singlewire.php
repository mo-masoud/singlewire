<?php

namespace MoMasoud\Singlewire;


use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Singlewire
{
    protected PendingRequest $client;

    protected function __construct(protected string $apiUrl, protected string $apiKey)
    {
        $this->client = Http::withToken($this->apiKey)
            ->baseUrl($this->apiUrl);
    }

    public static function make(string $apiUrl, string $apiKey): static
    {
        return new static($apiUrl, $apiKey);
    }

    /**
     * Get all devices from Singlewire without pagination
     *
     * @throw \Illuminate\Http\Client\RequestException
     *
     * @return Collection
     */
    public function devices(): Collection
    {

        $start = '0';
        $limit = '1000';

        $devices = collect([]);

        do {
            $response = $this->client->throw()->get("devices?limit=$limit&start=$start");

            $start = $response->json('next');

            $devices->push(...$response->json('data'));
        } while ($start !== null);

        return $devices;
    }
}
