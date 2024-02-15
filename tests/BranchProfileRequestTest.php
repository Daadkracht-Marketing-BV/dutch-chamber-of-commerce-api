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

    return $request->fetch();
}

it('can set the branch number', function () {
    performBranchRequest();

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

it('can parse the branch name in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Collection<TradeName> $tradeNames */
    $tradeNames = $response->getTradeNames();
    /** @var TradeName $firstTradeName */
    $firstTradeName = $tradeNames->first();
    expect($firstTradeName)->toBeInstanceOf(class: TradeName::class)->and($firstTradeName->getName())->toBe('Daadkracht Marketing B.V.');
});

it('can parse the branch address in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->getAddresses()->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->getStreetName())->toBe('De Opgang');
});

it('can parse the branch postal code in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->getAddresses()->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->getPostalCode())->toBe('9203GD');
});

it('can parse the branch city in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->getAddresses()->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->getCity())->toBe('Drachten');
});

it('can parse the branch country in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->getAddresses()->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->getCountry())->toBe('Nederland');
});

it('can parse the house number in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->getAddresses()->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->getHouseNumber())->toBe('2');
});

it('can parse the house number addition in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    /** @var Address $address */
    $address = $response->getAddresses()->first();
    expect($address)->toBeInstanceOf(class: Address::class)
        ->and($address->getHouseNumberAddition())->toBe('2');
});

it('can parse the branch fulltime employees in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getFulltimeEmployees())->toBe(3);
});

it('can parse the branch parttime employees in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getParttimeEmployees())->toBe(3);
});

it('can parse the branch total employees in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getTotalEmployees())->toBe(6);
});

it('can parse the branch statutory name in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getStatutoryName())->toBe('Daadkracht Marketing B.V.');
});

it('can parse the branch formal date of record in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getFormalDateOfRecord())->toBeInstanceOf(class: Carbon::class)->and(
        $response->getFormalDateOfRecord()->format('Y-m-d')
    )->toBe('2015-06-18');
});

it('can parse the branch material registration in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getMaterialRegistration())->toBeInstanceOf(class: Collection::class)
        ->and($response->getMaterialRegistration()->count())->toBe(1)
        ->and($response->getMaterialRegistration()->first()['type'])->toBe('dateStart')
        ->and($response->getMaterialRegistration()->first()['date']->format('Y-m-d'))->toBe('2011-05-18');
});

it('can parse the branch is main branch in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getIsMainBranch())->toBe(true);
});

it('can parse the branch is commercial branch in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getIsCommercialBranch())->toBe(true);
});

it('can parse the branch trade names in a branch profile API response', closure: function () {
    $response = performBranchRequest();
    expect($response->getFirstTradeName())->toBe('Daadkracht Marketing B.V.');
});
