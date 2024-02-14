<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use JsonSerializable;

class ForeignAddress implements JsonSerializable
{
    public function __construct(
        protected string $streetHouseNumber,
        protected string $zipCity,
        protected string $country
    ) {

    }

    public function getStreetHouseNumber(): string
    {
        return $this->streetHouseNumber;
    }

    public function getZipCity(): string
    {
        return $this->zipCity;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function serialize(): array
    {
        return [
            'streetHouseNumber' => $this->streetHouseNumber,
            'zipCity' => $this->zipCity,
            'country' => $this->country,
        ];
    }

    public function __serialize(): array
    {
        return $this->serialize();
    }

    public function jsonSerialize(): array
    {
        return $this->serialize();
    }
}
