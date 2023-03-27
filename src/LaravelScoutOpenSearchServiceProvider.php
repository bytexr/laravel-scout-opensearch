<?php

namespace ByteXR\LaravelScoutOpenSearch;

use ByteXR\LaravelScoutOpenSearch\Engines\OpenSearchEngine;
use ByteXR\LaravelScoutOpenSearch\Exceptions\OnlyAWSOrBasicAuthCredentials;
use ByteXR\LaravelScoutOpenSearch\Services\OpenSearchClient;
use Laravel\Scout\EngineManager;
use OpenSearch\ClientBuilder;

class LaravelScoutOpenSearchServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        resolve(EngineManager::class)->extend('opensearch', function () {
            return new OpenSearchEngine(
                new OpenSearchClient($this->createOpenSearchClient())
            );
        });
    }

    private function createOpenSearchClient(): \OpenSearch\Client
    {
        if (config('scout.opensearch.provider') == "aws") {
            return (new ClientBuilder())
                ->setHosts([config('scout.opensearch.host')])
                ->setSigV4CredentialProvider([
                    'key' => config('scout.opensearch.aws_access_key'),
                    'secret' => config('scout.opensearch.aws_secret_key'),
                ])
                ->setSigV4Region(config('scout.opensearch.aws_region'))
                ->build();
        }

        return (new ClientBuilder())
            ->setHosts([config('scout.opensearch.host')])
            ->setSSLVerification(config('scout.opensearch.ssl_verification', true))
            ->setBasicAuthentication(config('scout.opensearch.username'), config('scout.opensearch.password'))
            ->build();
    }
}
