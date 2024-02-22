# Package to interact with the Dutch Chamber of Commerce API (KVK)

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
    'api_key' => env('DUTCH_CHAMBER_OF_COMMERCE_API_KEY'),
];
```

## Usage

### Search API

Initiate a basic search request in the following way
```php
$response = DutchChamberOfCommerceApi::searchRequest()
    ->place('Drachten')
    ->streetName('De Opgang')
    ->fetch();
```

The acceptable chained search methods are:

- `cocNumber(string $cocNumber)` - The Chamber of Commerce number of the company
- `rsin(string $rsin)` - The RSIN of the company
- `branchNumber(string $branchNumber)` - The branch number of the company
- `name(string $name)` - The name of the company
- `place(string $place)` - The place of the company
- `streetName(string $streetName)` - The street name of the company
- `houseNumber(int $houseNumber)` - The house number of the company
- `houseLetter(string $houseLetter)` - The house letter of the company
- `postalCode(string $postalCode)` - The postal code of the company
- `place(string $place)` - The place of the company
- `poBoxNumber(int $poBoxNumber)` - The PO box number of the company
- `type(array|string $type)` - The type of the company (e.g. "Hoofdvestiging")
- `includeInactiveRegistrations(bool $includeInactiveRegistrations)` - Whether to include inactive registrations

You can also specify the number of results to return and the page number to return, in the following way:

```php
$response = DutchChamberOfCommerceApi::searchRequest()
    ->place('Leeuwarden')
    ->streetName('Sophialaan')
    ->resultsPerPage(10)
    ->page(2)
    ->fetch();
```

The `fetch` method will return a `SearchResponse` object with a `results` collection

The items in the `results` collection are `SearchResponseResultItem` objects, which have the following properties:

- `cocNumber` - The Chamber of Commerce number of the company
- `rsin` - The RSIN of the company
- `branchNumber` - The branch number of the company
- `name` - The name of the company
- `addresses` - The addresses of the company (a collection of `DomesticAddress` and/or `ForeignAddress`) objects. These differ from the generic `Address` object in that they have different properties.
- `type` - The type of the company/branch
- `active` - Whether the company/branch is active
- 

### Branches API

You can fetch the branches of a company by its Chamber of Commerce number in the following way:

```php
$response = DutchChamberOfCommerceApi::baseProfileBranchesRequest()
    ->cocNumber('12345678')
    ->fetch();
```

The `fetch` method will return a `BaseProfileBranchesResponse` object.

You can query the branches on this response object in the following way:

```php
$branches = $response->branches;
```

This will return a collection of `Branch` objects.

You can filter these with e.g.:

```php
$branches = $response->branches->where('isMainBranch', true);
```

A branch object will also allow you to call the `createFullBranchProfileRequest` method to fetch a full profile of the branch. (see below for more information on the full branch profile API)

This can be chained in the following way:

```php
$fullBranchProfile = DutchChamberOfCommerceApi::baseProfileBranchesRequest()
    ->cocNumber('63546167')
    ->fetch()
    ->branches->firstWhere('isMainBranch', true)
    ->createFullBranchProfileRequest()
    ->fetch();
```

A branch object has the following properties:

- `branchNumber` - The branch number of the branch
- `firstTradeName` - The first trade name of the branch
- `isMainBranch` - Whether the branch is the main branch of the company
- `isShieldedAddress` - Whether the address of the branch is shielded
- `isCommercialBranch` - Whether the branch is a commercial branch
- `fullAddress` - The full address of the branch

### (Full) Branch Profile API

You can fetch the profile of a branch by its branch number in the following way:

```php
$response = DutchChamberOfCommerceApi::branchProfileRequest()
    ->branchNumber('12345678')
    ->fetch();
```

If you also want to request geographical information, you can do so in the following way:

```php
$response = DutchChamberOfCommerceApi::branchProfileRequest()
    ->branchNumber('12345678')
    ->withGeo(true)
    ->fetch();
```

The `fetch` method will return a `BranchProfileResponse` object.

A branch profile response has the following properties:

- `branchNumber` - The branch number of the branch
- `cocNumber` - The Chamber of Commerce number of the company
- `rsin` - The RSIN of the company
- `nonMailingIndicator` - The non-mailing indicators of the company
- `formalDateOfRecord` - The formal date of record of the company (when the record was changed)
- `materialRegistration` - The material registration of the company
- `statutoryName` - The statutory name of the company
- `firstTradeName` - The first trade name of the company
- `isMainBranch` - Whether the branch is the main branch of the company
- `isCommercialBranch` - Whether the branch is a commercial branch
- `fulltimeEmployees` - The number of full-time employees of the branch
- `parttimeEmployees` - The number of part-time employees of the branch
- `totalEmployees` - The total number of employees of the branch
- `tradeNames` - The trade names of the branch (a collection of `TradeName` objects)
- `addresses` - The addresses of the branch (a collection of `Address` objects)
- `websites` - The websites of the branch (a collection of URLs)
- `sbiActivities` - The SBI activities of the branch (a collection of `SbiActivity` objects)

### Base Profile API

You can fetch the base profile of a company by its Chamber of Commerce number in the following way:

```php
$response = DutchChamberOfCommerceApi::baseProfileRequest()
    ->cocNumber('12345678')
    ->fetch();
```

If you also want to request geographical information, you can do so in the following way:

```php
$response = DutchChamberOfCommerceApi::baseProfileRequest()
    ->cocNumber('12345678')
    ->withGeo(true)
    ->fetch();
```

The `fetch` method will return a `BaseProfileResponse` object with the following properties:

- `cocNumber` - The Chamber of Commerce number of the company
- `nonMailingIndicator` - The non-mailing indicators of the company
- `name` - The name of the company
- `formalDateOfRecord` - The formal date of record of the company (when the record was changed)
- `totalNumberOfEmployees` - The total number of employees of the company
- `statutoryName` - The statutory name of the company
- `tradeNames` - The trade names of the company (a collection of `TradeName` objects)
- `sbiActivities` - The SBI activities of the company (a collection of `SbiActivity` objects)

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
