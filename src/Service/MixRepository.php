<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly HttpClientInterface $httpClient,
        #[Autowire('%kernel.debug%')]
        private bool $isDebug
    )
    {
    }
    
    public function findAll(): array
    {
        $httpClient = $this->httpClient;

        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem){ //use() is used to pass the $httpClient variable to the closure
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);

            $response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');

            return $response->toArray();
        });

    }
}