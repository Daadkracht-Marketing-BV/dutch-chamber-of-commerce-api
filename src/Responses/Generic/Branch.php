<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use JsonSerializable;

class Branch implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $branchNumber,
        public string $firstTradeName,
        public bool $isMainBranch,
        public bool $isShieldedAddress,
        public bool $isCommercialBranch,
        public string $fullAddress
    ) {

    }

    public function createFullBranchProfileRequest($testMode = false): BranchProfileRequest
    {
        $branchProfileRequest = new BranchProfileRequest($testMode);
        $branchProfileRequest->branchNumber($this->branchNumber);

        return $branchProfileRequest;
    }

    public function createBranchProfileRequest($testMode = false): BranchProfileRequest
    {
        return $this->createFullBranchProfileRequest($testMode);
    }
}
