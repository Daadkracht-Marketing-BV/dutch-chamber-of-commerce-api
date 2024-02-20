<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Facades\DutchChamberOfCommerceApi;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\Search\SearchRequest;

it('can instantiate a search request using the facade', function () {
    $searchRequest = DutchChamberOfCommerceApi::searchRequest();
    expect($searchRequest)->toBeInstanceOf(SearchRequest::class);
});

it('can instantiate a search request using the facade in test mode', function () {
    $searchRequest = DutchChamberOfCommerceApi::searchRequest(testMode: true);
    expect($searchRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v2/zoeken');
});

it('can instantiate a search request using the facade in live mode', function () {
    $searchRequest = DutchChamberOfCommerceApi::searchRequest();
    expect($searchRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v2/zoeken');
});

it('can instantiate a base profile request using the facade', function () {
    $baseProfileRequest = DutchChamberOfCommerceApi::baseProfileRequest();
    expect($baseProfileRequest)->toBeInstanceOf(\DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest::class);
});

it('can instantiate a base profile request using the facade in test mode', function () {
    $baseProfileRequest = DutchChamberOfCommerceApi::baseProfileRequest(testMode: true);
    expect($baseProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v1/basisprofielen');
});

it('can instantiate a base profile request using the facade in live mode', function () {
    $baseProfileRequest = DutchChamberOfCommerceApi::baseProfileRequest();
    expect($baseProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v1/basisprofielen');
});

it('can instantiate a base profile branches request using the facade', function () {
    $baseProfileBranchesRequest = DutchChamberOfCommerceApi::baseProfileBranchesRequest();
    expect($baseProfileBranchesRequest)->toBeInstanceOf(\DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfileBranches\BaseProfileBranchesRequest::class);
});

it('can instantiate a base profile branches request using the facade in test mode', function () {
    $baseProfileBranchesRequest = DutchChamberOfCommerceApi::baseProfileBranchesRequest(testMode: true);
    expect($baseProfileBranchesRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v1/basisprofielen');
});

it('can instantiate a base profile branches request using the facade in live mode', function () {
    $baseProfileBranchesRequest = DutchChamberOfCommerceApi::baseProfileBranchesRequest();
    expect($baseProfileBranchesRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v1/basisprofielen');
});

it('can instantiate a branch profile request using the facade', function () {
    $branchProfileRequest = DutchChamberOfCommerceApi::branchProfileRequest();
    expect($branchProfileRequest)->toBeInstanceOf(\DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest::class);
});

it('can instantiate a branch profile request using the facade in test mode', function () {
    $branchProfileRequest = DutchChamberOfCommerceApi::branchProfileRequest(testMode: true);
    expect($branchProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v1/vestigingsprofielen');
});

it('can instantiate a branch profile request using the facade in live mode', function () {
    $branchProfileRequest = DutchChamberOfCommerceApi::branchProfileRequest();
    expect($branchProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v1/vestigingsprofielen');
});

it('can instantiate a search request without using the facade', function () {
    $searchRequest = new SearchRequest();
    expect($searchRequest)->toBeInstanceOf(SearchRequest::class);
});

it('can instantiate a search request without using the facade in test mode', function () {
    $searchRequest = new SearchRequest(testMode: true);
    expect($searchRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v2/zoeken');
});

it('can instantiate a search request without using the facade in live mode', function () {
    $searchRequest = new SearchRequest();
    expect($searchRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v2/zoeken');
});

it('can instantiate a base profile request without using the facade', function () {
    $baseProfileRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest();
    expect($baseProfileRequest)->toBeInstanceOf(\DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest::class);
});

it('can instantiate a base profile request without using the facade in test mode', function () {
    $baseProfileRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest(testMode: true);
    expect($baseProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v1/basisprofielen');
});

it('can instantiate a base profile request without using the facade in live mode', function () {
    $baseProfileRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest();
    expect($baseProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v1/basisprofielen');
});

it('can instantiate a base profile branches request without using the facade', function () {
    $baseProfileBranchesRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfileBranches\BaseProfileBranchesRequest();
    expect($baseProfileBranchesRequest)->toBeInstanceOf(\DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfileBranches\BaseProfileBranchesRequest::class);
});

it('can instantiate a base profile branches request without using the facade in test mode', function () {
    $baseProfileBranchesRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfileBranches\BaseProfileBranchesRequest(testMode: true);
    expect($baseProfileBranchesRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v1/basisprofielen');
});

it('can instantiate a base profile branches request without using the facade in live mode', function () {
    $baseProfileBranchesRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfileBranches\BaseProfileBranchesRequest();
    expect($baseProfileBranchesRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v1/basisprofielen');
});

it('can instantiate a branch profile request without using the facade', function () {
    $branchProfileRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest();
    expect($branchProfileRequest)->toBeInstanceOf(\DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest::class);
});

it('can instantiate a branch profile request without using the facade in test mode', function () {
    $branchProfileRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest(testMode: true);
    expect($branchProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v1/vestigingsprofielen');
});

it('can instantiate a branch profile request without using the facade in live mode', function () {
    $branchProfileRequest = new \DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest();
    expect($branchProfileRequest->getApiEndpoint())->toBe('https://api.kvk.nl/api/v1/vestigingsprofielen');
});
