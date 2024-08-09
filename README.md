# Laravel Scout OpenSearch

<p align="center">
<a href="https://packagist.org/packages/bytexr/laravel-scout-opensearch"><img src="https://img.shields.io/packagist/dt/bytexr/laravel-scout-opensearch" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/bytexr/laravel-scout-opensearch"><img src="https://img.shields.io/packagist/v/bytexr/laravel-scout-opensearch" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/bytexr/laravel-scout-opensearch"><img src="https://img.shields.io/packagist/l/bytexr/laravel-scout-opensearch" alt="License"></a>
</p>

## Introduction

Laravel Scout OpenSearch simplifies the integration of Laravel Scout with OpenSearch, offering a seamless experience. Additionally, it boasts full compatibility with AWS OpenSearch, enabling hassle-free implementation.

## Installation

```shell
composer require bytexr/laravel-scout-opensearch
```

To make the necessary updates, navigate to config/scout.php and add the following code:

```php
return [
    ...

    'opensearch' => [
        'host' => env('OPENSEARCH_HOST', 'https://localhost:9200'),
        'access_key' => env('OPENSEARCH_ACCESS_KEY', 'admin'),
        'secret_key' => env('OPENSEARCH_SECRET_KEY', 'admin'),
        'options' => [
            'ssl_verification' => env('OPENSEARCH_SSL_VERIFICATION', true),
            // Used for AWS
            'sigv4_enabled' => env('OPENSEARCH_SIGV4_ENABLED', false),
            'sigv4_region' =>  env('OPENSEARCH_SIGV4_REGION', 'eu-west-1'),
        ],
    ],

];

```

Finally, ensure that all required environment variables are set in your `.env` file, and don't forget to set the `SCOUT_DRIVER` value to `opensearch`.

## Explicit Mapping

Should you need to specifically define the mapping for your indexes, you can do so by setting the `index-settings` key in config/scout.php as follows:

```php
return [
    ...
    
    'opensearch' => [
        ...
        'index-settings' => [
            Post::class => [
                'mappings' => [
                    'properties' => [
                        'id' => [
                            'type' => 'text',
                        ],
                        'title' => [
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ],
    ]
]
```

## License

Laravel Scout OpenSearch is open-sourced software licensed under the [MIT license](LICENSE).
