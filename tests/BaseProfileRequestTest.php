<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile\BaseProfileResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\SbiActivity;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\TradeName;
use Illuminate\Support\Facades\Http;

/**
 * @throws ApiException
 * @throws UnexpectedResponseException
 */
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
            $response->cocNumber
        )->toBe('63546167');
});

it('can parse the trade names in a base profile API response', function () {
    $response = performBaseProfileRequest();
    $tradeNames = $response->tradeNames;

    /** @var TradeName $firstTradeName */
    $firstTradeName = $tradeNames->first();
    expect($tradeNames->count())->toBe(1)->and(
        $firstTradeName->name
    )->toBe('Daadkracht Marketing B.V.')
        ->and(
            $firstTradeName->order
        )->toBe(0);
});

it('can parse the non mailing indicator in a base profile API response', function () {
    $response = performBaseProfileRequest();
    expect($response->nonMailingIndicator)->toBe(true);
});

it('can parse the statutory name in a base profile API response', function () {
    $response = performBaseProfileRequest();
    expect($response->statutoryName)->toBe('Daadkracht Marketing B.V.');
});

it('can parse the total number of employees in a base profile API response', function () {
    $response = performBaseProfileRequest();
    expect($response->totalNumberOfEmployees)->toBe(6);
});

it('can parse the SBI activities in a base profile API response', function () {
    $response = performBaseProfileRequest();
    $sbiActivities = $response->sbiActivities;

    /** @var SbiActivity $firstSbiActivity */
    $firstSbiActivity = $sbiActivities->first();
    expect($sbiActivities->count())->toBe(1)
        ->and(
            $firstSbiActivity->sbiCode
        )->toBe('7320')
        ->and(
            $firstSbiActivity->sbiDescription
        )->toBe('Markt- en opinieonderzoekbureaus')
        ->and(
            $firstSbiActivity->isMainActivity
        )->toBe(true);
});

it('can parse the name in a base profile API response', function () {
    $response = performBaseProfileRequest();
    expect($response->name)->toBe('Daadkracht Marketing B.V.');
});
