<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfileBranches\BaseProfileBranchesRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfileBranches\BaseProfileBranchesResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Branch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

function performBranchesRequest(): BaseProfileBranchesResponse
{
    $request = new BaseProfileBranchesRequest();
    $request->cocNumber('6354616744');

    Http::fake([
        'api.kvk.nl/*' => Http::sequence([
            Http::response(
                body: fixture('base-profile-branches-response')
            ),
        ]),
    ]);

    Http::preventStrayRequests();

    $response = $request->fetch();

    return $response;
}

it('can parse a base profile branches API response', function () {
    $response = performBranchesRequest();

    expect($response)
        ->toBeInstanceOf(class: BaseProfileBranchesResponse::class)
        ->and(
            $response->getCocNumber()
        )->toBe('63546167');
});

it('can parse the commercial branches in a base profile branches API response', function () {
    $response = performBranchesRequest();
    expect($response->getCommercialBranches())->toBe(1);
});

it('can parse the non-commercial branches in a base profile branches API response', function () {
    $response = performBranchesRequest();
    expect($response->getNonCommercialBranches())->toBe(0);
});

it('can parse the total branches in a base profile branches API response', function () {
    $response = performBranchesRequest();
    expect($response->getTotalBranches())->toBe(1);
});

it('can parse the branches in a base profile branches API response', function () {
    $response = performBranchesRequest();
    /** @var Collection<Branch> $branches */
    $branches = $response->getBranches();
    expect($branches->count())->toBe(1);
});

it('can parse the branch number in a base profile branches API response', function () {
    $response = performBranchesRequest();
    /** @var Collection<Branch> $branches */
    $branches = $response->getBranches();
    /** @var Branch $firstBranch */
    $firstBranch = $branches->first();
    expect($firstBranch->branchNumber)->toBe('000022655646');
});

it('can parse the branch name in a base profile branches API response', function () {
    $response = performBranchesRequest();
    /** @var Collection<Branch> $branches */
    $branches = $response->getBranches();
    /** @var Branch $firstBranch */
    $firstBranch = $branches->first();
    expect($firstBranch->firstTradeName)->toBe('Daadkracht Marketing B.V.');
});

it('can parse the branch is main branch in a base profile branches API response', function () {
    $response = performBranchesRequest();
    /** @var Collection<Branch> $branches */
    $branches = $response->getBranches();
    /** @var Branch $firstBranch */
    $firstBranch = $branches->first();
    expect($firstBranch->isMainBranch)->toBe(true);
});

it('can parse the branch is commercial branch in a base profile branches API response', function () {
    $response = performBranchesRequest();
    /** @var Collection<Branch> $branches */
    $branches = $response->getBranches();
    /** @var Branch $firstBranch */
    $firstBranch = $branches->first();
    expect($firstBranch->isCommercialBranch)->toBe(true);
});
