<?php

namespace ByteXR\LaravelScoutOpenSearch;

use ByteXR\LaravelScoutOpenSearch\Engines\OpenSearchEngine;
use ByteXR\LaravelScoutOpenSearch\Services\OpenSearchClient;
use Laravel\Scout\EngineManager;

class LaravelScoutOpenSearchServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        resolve(EngineManager::class)->extend('opensearch', function () {
            return new OpenSearchEngine(
                new OpenSearchClient($this->createOpenSearchClient()),
                config('scout.soft_delete')
            );
        });
    }

    private function createOpenSearchClient(): \OpenSearch\Client
    {
        $client = (new \OpenSearch\ClientBuilder())
            ->setHosts([config('scout.opensearch.host')])
            ->setSSLVerification(config('scout.opensearch.options.ssl_verification'));

        if (config('scout.opensearch.options.sigv4_enabled')) {
            $client = $client->setSigV4CredentialProvider([
                'key' => config('scout.opensearch.access_key'),
                'secret' => config('scout.opensearch.secret_key'),
            ])
                             ->setSigV4Region(config('scout.opensearch.options.sigv4_region'));
        } else {
            $client = $client->setBasicAuthentication(config('scout.opensearch.access_key'), config('scout.opensearch.secret_key'));
        }

        return $client->build();
    }
}
