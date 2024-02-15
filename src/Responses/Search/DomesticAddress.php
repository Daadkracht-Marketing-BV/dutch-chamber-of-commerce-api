<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use JsonSerializable;

class DomesticAddress implements JsonSerializable
{
    public function __construct(
        public string $streetName,
        public ?string $houseNumber,
        public ?string $houseLetter,
        public ?int $poBoxNumber,
        public ?string $postalCode,
        public string $city
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
