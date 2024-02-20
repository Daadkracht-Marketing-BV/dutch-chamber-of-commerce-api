<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BranchProfile\BranchProfileResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Address;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\TradeName;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

it('can create a branch profile request', function () {
    $request = new BranchProfileRequest();
    expect($request)->toBeInstanceOf(class: BranchProfileRequest::class);
});

function performBranchRequest(): BranchProfileResponse
{
    $request = new BranchProfileRequest();
    $request->branchNumber('000022655646');

    Http::fake([
        'api.kvk.nl/*' => Http::sequence([
            Http::response(
                body: fixture('branch-profile-response')
            ),
        ]),
    ]);

    Http::preventStrayRequests();

    return $request->fetch();
}

it('can set the branch number', function () {
    performBranchRequest();

    Http::assertSent(
        fn (Request $request) => $request->url() === 'https://api.kvk.nl/api/v1/vestigingsprofielen/000022655646?geoData=False'
    );
});

it('can parse a branch profile API response', function () {
    $response = performBranchRequest();

    expect($response)
        ->toBeInstanceOf(class: BranchProfileResponse::class)
        ->and(
            $response->branchNumber
        )->toBe('000022655646');
});

it('can parse the branch name in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Collection<TradeName> $tradeNames */
    $tradeNames = $response->tradeNames;
    /** @var TradeName $firstTradeName */
    $firstTradeName = $tradeNames->first();
    expect($firstTradeName)->toBeInstanceOf(class: TradeName::class)->and($firstTradeName->name)->toBe('Daadkracht Marketing B.V.');
});

it('can parse the branch address in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->addresses->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->streetName)->toBe('De Opgang')
        ->and($address->postalCode)->toBe('9203GD')
        ->and($address->city)->toBe('Drachten')
        ->and($address->country)->toBe('Nederland')
        ->and($address->houseNumber)->toBe('2')
        ->and($address->houseNumberAddition)->toBe('2');
});

it('can parse the branch postal code in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->addresses->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->postalCode)->toBe('9203GD');
});

it('can parse the branch city in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->addresses->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->city)->toBe('Drachten');
});

it('can parse the branch country in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->addresses->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->country)->toBe('Nederland');
});

it('can parse the house number in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->addresses->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->houseNumber)->toBe('2');
});

it('can parse the house number addition in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->addresses->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->houseNumberAddition)->toBe('2');
});

it('can parse the branch fulltime employees in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->fulltimeEmployees)->toBe(3);
});

it('can parse the branch parttime employees in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->parttimeEmployees)->toBe(3);
});

it('can parse the branch total employees in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->totalEmployees)->toBe(6);
});

it('can parse the branch statutory name in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->statutoryName)->toBe('Daadkracht Marketing B.V.');
});

it('can parse the branch formal date of record in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->formalDateOfRecord)->toBeInstanceOf(class: Carbon::class)->and(
        $response->formalDateOfRecord->format('Y-m-d')
    )->toBe('2015-06-18');
});

it('can parse the branch material registration in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->materialRegistration)->toBeInstanceOf(class: Collection::class)
        ->and($response->materialRegistration->count())->toBe(1)
        ->and($response->materialRegistration->first()['type'])->toBe('dateStart')
        ->and($response->materialRegistration->first()['date']->format('Y-m-d'))->toBe('2011-05-18');
});

it('can parse the branch is main branch in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->isMainBranch)->toBe(true);
});

it('can parse the branch is commercial branch in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->isCommercialBranch)->toBe(true);
});

it('can parse the branch trade names in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->firstTradeName)->toBe('Daadkracht Marketing B.V.');
});
