<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use JsonSerializable;

class ForeignAddress implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $streetHouseNumber,
        public string $zipCity,
        public string $country
    ) {

    }
}
