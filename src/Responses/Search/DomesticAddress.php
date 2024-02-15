<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use JsonSerializable;

class DomesticAddress implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $streetName,
        public ?string $houseNumber,
        public ?string $houseLetter,
        public ?int $poBoxNumber,
        public ?string $postalCode,
        public string $city
    ) {
    }
}
