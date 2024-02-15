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

    public function __toString(): string
    {
        // get public properties
        $properties = get_object_vars($this);

        return json_encode($properties);
    }

    public function jsonSerialize(): mixed
    {
        return json_decode($this->__toString(), true);
    }
}
