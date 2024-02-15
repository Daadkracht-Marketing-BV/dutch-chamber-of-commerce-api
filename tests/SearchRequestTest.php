<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\Search\SearchRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search\DomesticAddress;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search\ForeignAddress;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search\SearchResponseResultItem;
use Illuminate\Support\Facades\Http;

it('can create a search request', function () {
    $request = new SearchRequest();
    expect($request)->toBeInstanceOf(class: SearchRequest::class);
});

it('can set the COC number', function () {
    $request = new SearchRequest();
    $request->cocNumber('63546167');
    expect(
        $request->getQueryString()
    )->toBe('kvkNummer=63546167');
});

it('can set the RSIN', function () {
    $request = new SearchRequest();
    $request->rsin('123456789');
    expect(
        $request->getQueryString()
    )->toBe('rsin=123456789');
});

it('can set the branch number', function () {
    $request = new SearchRequest();
    $request->branchNumber('000000000');
    expect(
        $request->getQueryString()
    )->toBe('vestigingsnummer=000000000');
});

it('can set the name', function () {
    $request = new SearchRequest();
    $request->name('Daadkracht Marketing');
    expect(
        $request->getQueryString()
    )->toBe('naam=Daadkracht+Marketing');
});

it('can unset a query parameter if it is null', function () {
    $request = new SearchRequest();
    $request->name('Daadkracht Marketing');
    $request->name(null);
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
    $request->includeInactiveRegistrations(true);
    expect(
        $request->getQueryString()
    )->toBe('InclusiefInactieveRegistraties=true', 'InclusiefInactieveRegistraties should be true');
    $request->includeInactiveRegistrations(false);
    expect(
        $request->getQueryString()
    )->toBe('InclusiefInactieveRegistraties=false', 'InclusiefInactieveRegistraties should be false');
});

function performBasicSearch(): \DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search\SearchResponse
{
    $request = new SearchRequest();
    $request->cocNumber('63546167');

    Http::preventStrayRequests();

    Http::fake([
        'api.kvk.nl/*' => Http::sequence([
            Http::response(
                body: fixture('search-response')
            ),
        ]),
    ]);

    $response = $request->fetch();

    return $response;
}

it('can parse a search API response', function () {
    $response = performBasicSearch();

    /** @var SearchResponseResultItem $firstResult */
    $firstResult = $response->getResults()->first();
    expect($firstResult)
        ->toBeInstanceOf(
            class: SearchResponseResultItem::class
        )
        ->and(
            $firstResult->getCocNumber()
        )->toBe('63546167');
});

it('can parse a search API response with multiple results', function () {
    $response = performBasicSearch();
    expect($response->getResults()->count())->toBe(2);
});

it('can parse the branch number in a search API response', function () {
    $response = performBasicSearch();
    /** @var SearchResponseResultItem $firstResult */
    $firstResult = $response->getResults()->first();
    expect($firstResult->getBranchNumber())->toBe('000022655646');
});

it('can parse the result type in a search API response', function () {
    $response = performBasicSearch();
    /** @var SearchResponseResultItem $firstResult */
    $firstResult = $response->getResults()->first();
    expect($firstResult->getType())->toBe('hoofdvestiging');
});

it('can parse the name in a search API response', function () {
    $response = performBasicSearch();
    /** @var SearchResponseResultItem $firstResult */
    $firstResult = $response->getResults()->first();
    expect($firstResult->getName())->toBe('Daadkracht Marketing B.V.');
});

it('can parse the address in a search API response', function () {
    $response = performBasicSearch();
    /** @var SearchResponseResultItem $firstResult */
    $firstResult = $response->getResults()->first();
    /** @var DomesticAddress|ForeignAddress $address */
    $address = $firstResult->getAddresses()->first();
    expect($address)
        ->toBeInstanceOf(
            class: DomesticAddress::class
        )
        ->and(
            $address->getStreetName()
        )->toBe('De Opgang');
});

it('generates an exception if an invalid COC number is searched for', function () {
    $request = new SearchRequest();
    $request->cocNumber('6354616744');
    Http::fake([
        'api.kvk.nl/*' => Http::response(
            body: fixture('search-response-invalid-cocnumber'),
            status: 400
        ),
    ]);
    $request->fetch();
})->throws(\DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiException::class, 'Het KVK-nummer 6354616744 is niet valide.');
