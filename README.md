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
        // default - this provider will use basic authentication username and password
        // aws - this provider will use AWS credentials to authenticate 
        "provider"         => env('OPENSEARCH_PROVIDER', 'default'),
        "host"             => env('OPENSEARCH_HOST', 'https://localhost:9200'),
        "username"         => env('OPENSEARCH_USERNAME', 'admin'),
        "password"         => env('OPENSEARCH_PASSWORD', 'admin'),
        "ssl_verification" => env("OPENSEARCH_SSL_VERIFICATION", true),

        // Only necessary if project is using AWS provider
        "aws_access_key"   => env('OPENSEARCH_AWS_ACCESS_KEY'),
        "aws_secret_key"   => env('OPENSEARCH_AWS_SECRET_KEY'),
        "aws_region"       => env('OPENSEARCH_AWS_REGION', 'eu-west-1'),
    ],

];

```

Finally, ensure that all required environment variables are set in your `.env` file, and don't forget to set the `SCOUT_DRIVER` value to `opensearch`.

## License

Laravel Scout OpenSearch is open-sourced software licensed under the [MIT license](LICENSE).