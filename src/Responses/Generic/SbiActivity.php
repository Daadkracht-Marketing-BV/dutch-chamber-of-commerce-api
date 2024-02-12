<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

class SbiActivity
{
    public function __construct(protected string $sbiCode, protected string $sbiDescription, protected bool $isMainActivity)
    {

    }

    public function getSbiCode(): string
    {
        return $this->sbiCode;
    }

    public function getSbiDescription(): string
    {
        return $this->sbiDescription;
    }

    public function getIsMainActivity(): bool
    {
        return $this->isMainActivity;
    }
}
