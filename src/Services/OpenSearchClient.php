<?php

namespace ByteXR\LaravelScoutOpenSearch\Services;

use Illuminate\Support\Collection;
use OpenSearch\Client;

class OpenSearchClient
{
    public function __construct(private Client $client)
    {
    }

    public function createIndex(string $index): void
    {
        $this->client->indices()->create([
            'index' => $index,
        ]);
    }

    public function deleteIndex(string $index): void
    {
        $this->client->indices()->delete([
            'index' => $index,
        ]);
    }

    public function bulkUpdate(string $index, $models): callable|array
    {
        $data = [];

        $models->each(function ($model) use ($index, &$data) {
            $data[] = [
                'index' => [
                    '_index' => $index,
                    '_id'    => $model['objectID'],
                ],
            ];
            $data[] = $model;
        });

        return $this->client->bulk([
            'index' => $index,
            'body'  => $data,
        ]);
    }

    public function bulkDelete(string $index, Collection $keys): callable|array
    {
        $data = $keys->map(function ($key) use ($index) {
            return [
                'delete' => [
                    '_index' => $index,
                    '_id'    => $key,
                ],
            ];
        })->toArray();

        return $this->client->bulk([
            'index' => $index,
            'body'  => $data,
        ]);
    }

    public function search(string $index, string $query, array $options = [])
    {
        return $this->client->search(array_merge([
            'index' => $index,
            'body'  => [
                'query' => [
                    'bool' => [
                        'should'               => [
                            [
                                'query_string' => [
                                    'query'    => !empty($query) ? "*$query*" : '*',
                                    'analyzer' => 'keyword',
                                ],
                            ],
                            [
                                'multi_match' => [
                                    'query'    => $query,
                                    'analyzer' => 'keyword',
                                ],
                            ],
                        ],
                        'minimum_should_match' => 1
                    ],
                ],
            ],
        ], $options));
    }
}
