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
        private readonly HttpClientInterface $githubContentClient,
        #[Autowire('%kernel.debug%')]
        private bool $isDebug
    )
    {
    }
    
    public function findAll(): array
    {
        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem){ //use() is used to pass the $httpClient variable to the closure
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);

            $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');

            return $response->toArray();
        });

    }
}