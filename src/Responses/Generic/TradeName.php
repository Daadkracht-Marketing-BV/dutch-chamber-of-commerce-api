<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use JsonSerializable;

class TradeName implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $name,
        public int $order
    ) {

    }
}
