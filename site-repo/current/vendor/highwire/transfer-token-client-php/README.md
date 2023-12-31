# transfer-token-client-php
Transfer token generated API documentation for web use.

This PHP package is automatically generated by the [Swagger Codegen](https://github.com/swagger-api/swagger-codegen) project:

- API version: 1.0.0
- Build package: io.swagger.codegen.languages.PhpClientCodegen
For more information, please visit [http://www.highwirepress.com](http://www.highwirepress.com)

## Requirements

PHP 5.5 and later

## Installation & Usage
### Composer

To install the bindings via [Composer](http://getcomposer.org/), add the following to `composer.json`:

```
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/highwire/transfer-token-client-php.git"
    }
  ],
  "require": {
    "highwire/transfer-token-client-php": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
    require_once('/path/to/transfer-token-client-php/vendor/autoload.php');
```

## Tests

To run the unit tests:

```
composer install
./vendor/bin/phpunit
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new TransferTokenClient\Api\AuthRequestsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$for_host = "for_host_example"; // string | is the value of the Referer header on the request, if any, eg, the IHS Global portal
$for_ip = "for_ip_example"; // string | is the IP address of the user, asserted by the requester, is required
$token = "token_example"; // string | Signed Base64-encoded JWT
$via_host = "via_host_example"; // string | is the value of the Host header on the request, if any, eg, www.accessengineeringlibrary.com
$target = "target_example"; // string | target uri

try {
    $result = $apiInstance->getAuth($for_host, $for_ip, $token, $via_host, $target);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuthRequestsApi->getAuth: ', $e->getMessage(), PHP_EOL;
}

?>
```

## Documentation for API Endpoints

All URIs are relative to *https://fr-docker-host-dev-01.highwire.org:9015*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AuthRequestsApi* | [**getAuth**](docs/Api/AuthRequestsApi.md#getauth) | **GET** /api/transfer/auth | Get Auth
*TokenRequestsApi* | [**getTransferToken**](docs/Api/TokenRequestsApi.md#gettransfertoken) | **GET** /api/transfer/token | Get Transfer Token


## Documentation For Models

 - [Auth](docs/Model/Auth.md)
 - [Token](docs/Model/Token.md)


## Documentation For Authorization

 All endpoints do not require authorization.


## Author

spatel@highwirepress.com


