<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use JsonSerializable;

class SbiActivity implements JsonSerializable
{
    public function __construct(
        public string $sbiCode,
        public string $sbiDescription,
        public bool $isMainActivity
    ) {

    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize());
    }
}
