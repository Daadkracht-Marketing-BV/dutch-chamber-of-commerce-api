<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi;

class DutchChamberOfCommerceApi
{
    public function searchRequest($testMode = false): Requests\Search\SearchRequest
    {
        return new Requests\Search\SearchRequest($testMode);
    }

    public function baseProfileRequest($testMode = false): Requests\BaseProfile\BaseProfileRequest
    {
        return new Requests\BaseProfile\BaseProfileRequest($testMode);
    }

    public function baseProfileBranchesRequest($testMode = false): Requests\BaseProfileBranches\BaseProfileBranchesRequest
    {
        return new Requests\BaseProfileBranches\BaseProfileBranchesRequest($testMode);
    }

    public function branchProfileRequest($testMode = false): Requests\BranchProfile\BranchProfileRequest
    {
        return new Requests\BranchProfile\BranchProfileRequest($testMode);
    }
}
