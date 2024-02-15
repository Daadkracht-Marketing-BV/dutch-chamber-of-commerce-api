<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use JsonSerializable;

class ForeignAddress implements JsonSerializable
{
    public function __construct(
        public string $streetHouseNumber,
        public string $zipCity,
        public string $country
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
