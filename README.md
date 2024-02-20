# Package to interact with the Dutch Chamber of Commerce API (KVK)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/daadkracht-marketing-bv/dutch-chamber-of-commerce-api.svg?style=flat-square)](https://packagist.org/packages/daadkracht-marketing-bv/dutch-chamber-of-commerce-api)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/daadkracht-marketing-bv/dutch-chamber-of-commerce-api/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/daadkracht-marketing-bv/dutch-chamber-of-commerce-api/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/daadkracht-marketing-bv/dutch-chamber-of-commerce-api/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/daadkracht-marketing-bv/dutch-chamber-of-commerce-api/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/daadkracht-marketing-bv/dutch-chamber-of-commerce-api.svg?style=flat-square)](https://packagist.org/packages/daadkracht-marketing-bv/dutch-chamber-of-commerce-api)

This package provides a simple way to interact with the Dutch Chamber of Commerce API (KVK).

## Requirements

You need to have a valid API key from the Dutch Chamber of Commerce. You can request one [here](https://developers.kvk.nl/).

## Features

- Search for companies by name, address, postal code, city, and more
- Fetch the base profile of a company by its Chamber of Commerce number
- Fetch the branches of a company by its Chamber of Commerce number
- Fetch the profile of a branch by its branch number
- Requests are chainable

## Installation

You can install the package via composer:

```bash
composer require daadkracht-marketing-bv/dutch-chamber-of-commerce-api
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="dutch-chamber-of-commerce-api-config"
```

These are the contents of the published config file:

```php
return [
    'api_key' => env('DUTCH_CHAMBER_OF_COMMERCE_API_KEY', ''),
];
```

## Usage

```php
// try one of these
$response = DutchChamberOfCommerceApi::searchRequest()
    ->place('Drachten')
    ->streetName('De Opgang')
    ->fetch();
    
// search results will be in the $response->results collection

// get the coc number of the first result
$response->results->first()->cocNumber;

// get the profile of a company by its coc number
$response = DutchChamberOfCommerceApi::baseProfileRequest()
    ->cocNumber('12345678')
    ->fetch();

// get the branches of a company by its coc number
$response = DutchChamberOfCommerceApi::baseProfileBranchesRequest()
    ->cocNumber('12345678')
    ->fetch();

// get the profile of a branch by its branch number
$response = DutchChamberOfCommerceApi::branchProfileRequest()
    ->branchNumber('12345678')
    ->fetch();

// get a certain page from a search request
$response = DutchChamberOfCommerceApi::searchRequest()
    ->place('Drachten')
    ->streetName('De Opgang')
    ->page(2)
    ->fetch();

// get a iterator for a search request, and loop through the fetched companies
$paginator = DutchChamberOfCommerceApi::searchRequest()
    ->place('Drachten')
    ->streetName('De Opgang')
    ->fetch()
    ->resultIterator();

foreach ($paginator as $result) {
    echo $result->cocNumber;
}

// get the addresses for a branch
$response = DutchChamberOfCommerceApi::branchProfileRequest()
  ->branchNumber("000022655646")
  ->fetch();

$addresses = $response->addresses;
// get the first visitable address
$firstVisitableAddress = $addresses->firstWhere("type", "bezoekadres");
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Meta Bergman](https://github.com/drownthewitch)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
