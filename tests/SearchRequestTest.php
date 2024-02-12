<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\Search\SearchRequest;
use Illuminate\Support\Facades\Http;

it('can create a search request', function () {
    $request = new SearchRequest();
    expect($request)->toBeInstanceOf(class: SearchRequest::class);
});

it('can set the COC number', function () {
    $request = new SearchRequest();
    $request->setCocNumber('63546167');
    expect(
        $request->getQueryString()
    )->toBe('kvkNummer=63546167');
});

it('can set the RSIN', function () {
    $request = new SearchRequest();
    $request->setRsin('123456789');
    expect(
        $request->getQueryString()
    )->toBe('rsin=123456789');
});

it('can set the branch number', function () {
    $request = new SearchRequest();
    $request->setBranchNumber('000000000');
    expect(
        $request->getQueryString()
    )->toBe('vestigingsnummer=000000000');
});

it('can set the name', function () {
    $request = new SearchRequest();
    $request->setName('Daadkracht Marketing');
    expect(
        $request->getQueryString()
    )->toBe('naam=Daadkracht+Marketing');
});

it('can unset a query parameter if it is null', function () {
    $request = new SearchRequest();
    $request->setName('Daadkracht Marketing');
    $request->setName(null);
    expect(
        $request->getQueryString()
    )->toBe('');
});

it('can set the endpoint to the test endpoint', function () {
    $request = new SearchRequest(testMode: true);
    expect($request->getApiEndpoint())->toBe('https://api.kvk.nl/test/api/v2/zoeken');
});

it('can set the endpoint to the live endpoint', function () {
    $request = new SearchRequest();
    expect($request->getApiEndpoint())->toBe('https://api.kvk.nl/api/v2/zoeken');
});

it('correctly sets a boolean value formatted for the kvk api', function () {
    $request = new SearchRequest();
    $request->setIncludeInactiveRegistrations(true);
    expect(
        $request->getQueryString()
    )->toBe('InclusiefInactieveRegistraties=true', 'InclusiefInactieveRegistraties should be true');
    $request->setIncludeInactiveRegistrations(false);
    expect(
        $request->getQueryString()
    )->toBe('InclusiefInactieveRegistraties=false', 'InclusiefInactieveRegistraties should be false');
});

it('can parse a search API response', function () {
    $request = new SearchRequest();
    $request->setCocNumber('63546167');

    Http::preventStrayRequests();

    Http::fake([
        'api.kvk.nl/*' => Http::sequence([
            Http::response(
                body: fixture('search-response')
            ),
        ]),
    ]);

    $response = $request->get();
});
