<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use JsonSerializable;

class SbiActivity implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $sbiCode,
        public string $sbiDescription,
        public bool $isMainActivity
    ) {

    }
}
