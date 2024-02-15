<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use JsonSerializable;

class TradeName implements JsonSerializable
{
    public function __construct(
        public string $name,
        public int $order
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
