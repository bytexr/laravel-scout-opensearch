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
        if (
            (config('scout.opensearch.username') || config('scout.opensearch.password')) &&
            (config('scout.opensearch.aws_access_key') || config('scout.opensearch.aws_secret_key'))
        ) {
            throw new OnlyAWSOrBasicAuthCredentials("Your OpenSearch configuration should have onlu AWS or Basic Auth credentials and not both.");
        }

        if (config('scout.opensearch.username')) {
            return (new ClientBuilder())
                ->setHosts([config('scout.opensearch.host')])
                ->setSSLVerification(config('scout.opensearch.ssl_verification', true))
                ->setBasicAuthentication(config('scout.opensearch.username'), config('scout.opensearch.password'))
                ->build();
        }

        return (new ClientBuilder())
            ->setHosts([config('scout.opensearch.host')])
            ->setSigV4CredentialProvider([
                "key"    => config('scout.opensearch.aws_access_key'),
                "secret" => config('scout.opensearch.aws_secret_key'),
            ])
            ->setSigV4Region(confiig('scout.opensearch.aws_region'))
            ->build();
    }
}
