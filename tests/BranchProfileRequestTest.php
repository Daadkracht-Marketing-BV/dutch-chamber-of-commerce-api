<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BranchProfile\BranchProfileResponse;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('can create a branch profile request', function () {
    $request = new BranchProfileRequest();
    expect($request)->toBeInstanceOf(class: BranchProfileRequest::class);
});

/**
 * @return BranchProfileResponse
 */
function performBranchRequest(): BranchProfileResponse
{
    $request = new BranchProfileRequest();
    $request->setBranchNumber('000022655646');

    Http::fake([
        'api.kvk.nl/*' => Http::sequence([
            Http::response(
                body: fixture('branch-profile-response')
            ),
        ]),
    ]);

    Http::preventStrayRequests();

    $response = $request->get();

    return $response;
}

it('can set the branch number', function () {
    $response = performBranchRequest();

    Http::assertSent(
        fn (Request $request) => $request->url() === 'https://api.kvk.nl/api/v1/vestigingsprofielen/000022655646'
    );
});

it('can parse a branch profile API response', function () {
    $response = performBranchRequest();

    expect($response)
        ->toBeInstanceOf(class: BranchProfileResponse::class)
        ->and(
            $response->getBranchNumber()
        )->toBe('000022655646');
});

it('can parse the branch name in a branch profile API response', function () {
    $response = performBranchRequest();
    expect($response->getTradeNames()->first())->toBe('Daadkracht Marketing B.V.');
});

it('can parse the branch address in a branch profile API response', function () {
    $response = performBranchRequest();
    $address = $response->getAddresses()->first();
    expect()->toBe('De Opgang');
});
