<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

class Branch implements \JsonSerializable
{
    public function __construct(
        protected string $branchNumber,
        protected string $firstTradeName,
        protected bool $isMainBranch,
        protected bool $isShieldedAddress,
        protected bool $isCommercialBranch,
        protected string $fullAddress
    ) {

    }

    public function getBranchNumber(): string
    {
        return $this->branchNumber;
    }

    public function getFirstTradeName(): string
    {
        return $this->firstTradeName;
    }

    public function getIsMainBranch(): bool
    {
        return $this->isMainBranch;
    }

    public function getIsShieldedAddress(): bool
    {
        return $this->isShieldedAddress;
    }

    public function getIsCommercialBranch(): bool
    {
        return $this->isCommercialBranch;
    }

    public function getFullAddress(): string
    {
        return $this->fullAddress;
    }

    public function serialize(): array
    {
        return [
            'branchNumber' => $this->branchNumber,
            'firstTradeName' => $this->firstTradeName,
            'isMainBranch' => $this->isMainBranch,
            'isShieldedAddress' => $this->isShieldedAddress,
            'isCommercialBranch' => $this->isCommercialBranch,
            'fullAddress' => $this->fullAddress,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->serialize();
    }

    public function __serialize(): array
    {
        return $this->serialize();
    }
}
