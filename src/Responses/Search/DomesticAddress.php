<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use JsonSerializable;

class DomesticAddress implements JsonSerializable
{
    public function __construct(
        protected string $streetName,
        protected ?string $houseNumber,
        protected ?string $houseLetter,
        protected ?int $poBoxNumber,
        protected ?string $postalCode,
        protected string $city
    ) {
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function getHouseLetter(): ?string
    {
        return $this->houseLetter;
    }

    public function getPoBoxNumber(): ?int
    {
        return $this->poBoxNumber;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function serialize(): array
    {
        return [
            'streetName' => $this->streetName,
            'houseNumber' => $this->houseNumber,
            'houseLetter' => $this->houseLetter,
            'poBoxNumber' => $this->poBoxNumber,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
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
