<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use JsonSerializable;

class SbiActivity implements JsonSerializable
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

    public function serialize(): array
    {
        return [
            'sbiCode' => $this->sbiCode,
            'sbiDescription' => $this->sbiDescription,
            'isMainActivity' => $this->isMainActivity,
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
