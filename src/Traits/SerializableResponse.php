<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits;

use JsonSerializable;

trait SerializableResponse
{
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize());
    }
}
