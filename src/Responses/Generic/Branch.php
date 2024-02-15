<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile\BranchProfileRequest;
use JsonSerializable;

class Branch implements JsonSerializable
{
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

    public function __toString(): string
    {
        // get public properties
        $properties = get_object_vars($this);

        return json_encode($properties);
    }

    public function jsonSerialize(): array
    {
        return json_decode($this->__toString());
    }
}
