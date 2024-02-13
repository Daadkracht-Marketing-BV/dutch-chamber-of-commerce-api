<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use Illuminate\Support\Collection;

class Address
{
    public function __construct(
        protected string $type,
        protected ?bool $isShielded,
        protected ?string $fullAddress,
        protected ?string $streetName,
        protected ?string $houseNumber,
        protected ?string $houseNumberAddition,
        protected ?string $houseLetter,
        protected ?string $addressSupplement,
        protected ?string $postalCode,
        protected ?int $poBox,
        protected ?string $city,
        protected ?string $country,
        protected ?string $region,
        protected ?Collection $geoData,
    ) {

    }

    public static function fromResponse(array $responseData)
    {
        return new self(
            type: $responseData['type'],
            isShielded: $responseData['isShielded'] ?? null,
            fullAddress: $responseData['fullAddress'] ?? null,
            streetName: $responseData['streetName'] ?? null,
            houseNumber: $responseData['houseNumber'] ?? null,
            houseNumberAddition: $responseData['houseNumberAddition'] ?? null,
            houseLetter: $responseData['houseLetter'] ?? null,
            addressSupplement: $responseData['addressSupplement'] ?? null,
            postalCode: $responseData['postalCode'] ?? null,
            poBox: $responseData['poBox'] ?? null,
            city: $responseData['city'] ?? null,
            country: $responseData['country'] ?? null,
        );
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIsShielded(): ?bool
    {
        return $this->isShielded;
    }

    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function getHouseNumberAddition(): ?string
    {
        return $this->houseNumberAddition;
    }

    public function getHouseLetter(): ?string
    {
        return $this->houseLetter;
    }

    public function getAddressSupplement(): ?string
    {
        return $this->addressSupplement;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getPoBox(): ?int
    {
        return $this->poBox;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function serialize(): array
    {
        return [
            'type' => $this->type,
            'isShielded' => $this->isShielded,
            'fullAddress' => $this->fullAddress,
            'streetName' => $this->streetName,
            'houseNumber' => $this->houseNumber,
            'houseNumberAddition' => $this->houseNumberAddition,
            'houseLetter' => $this->houseLetter,
            'addressSupplement' => $this->addressSupplement,
            'postalCode' => $this->postalCode,
            'poBox' => $this->poBox,
            'city' => $this->city,
            'country' => $this->country,
        ];
    }

    public function __serialize(): array
    {
        return $this->serialize();
    }
}
