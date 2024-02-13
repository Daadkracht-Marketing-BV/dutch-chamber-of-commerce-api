<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

class Branch
{
    public function __construct(
        protected string $branchNumber,
        protected string $firstTradeName,
        protected bool $isMainBranch,
        protected bool $isProtectedAddress,
        protected bool $isCommercialBranch,
        protected string $fullAddress
    )
    {

    }
}
