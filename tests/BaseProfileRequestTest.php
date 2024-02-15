<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile\BaseProfileResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\TradeName;
use Illuminate\Support\Facades\Http;

function performBaseProfileRequest(): BaseProfileResponse
{
    Http::preventStrayRequests();

    Http::fake([
        'api.kvk.nl/*' => Http::sequence([
            Http::response(
                body: fixture('base-profile-response')
            ),
        ]),
    ]);

    $request = new BaseProfileRequest();

    return $request
        ->cocNumber('63546167')
        ->fetch();
}

it('can parse a base profile API response', function () {
    $response = performBaseProfileRequest();

    expect($response)
        ->toBeInstanceOf(class: BaseProfileResponse::class)
        ->and(
            $response->getCocNumber()
        )->toBe('63546167');
});

it('can parse the trade names in a base profile API response', function () {
    $response = performBaseProfileRequest();
    $tradeNames = $response->getTradeNames();

    /** @var TradeName $firstTradeName */
    $firstTradeName = $tradeNames->first();
    expect($tradeNames->count())->toBe(1)->and(
        $firstTradeName->getName()
    )->toBe('Daadkracht Marketing B.V.')
    ->and(
        $firstTradeName->getOrder()
    )->toBe(0);
});

it('can parse the non mailing indicator in a base profile API response', function () {
    $response = performBaseProfileRequest();
    expect($response->getNonMailingIndicator())->toBe(true);
});

it('can parse the statutory name in a base profile API response', function () {
    $response = performBaseProfileRequest();
    expect($response->getStatutoryName())->toBe('Daadkracht Marketing B.V.');
});

it('can parse the total number of employees in a base profile API response', function () {
    $response = performBaseProfileRequest();
    expect($response->getTotalNumberOfEmployees())->toBe(6);
});
