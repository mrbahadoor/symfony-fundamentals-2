<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class MixRepository
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly HttpClientInterface $httpClient)
    {
    }
    
    public function findAll(): array
    {
        $httpClient = $this->httpClient;

        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem){ //use() is used to pass the $httpClient variable to the closure
            $cacheItem->expiresAfter(5);

            $response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');

            return $response->toArray();
        });

    }
}