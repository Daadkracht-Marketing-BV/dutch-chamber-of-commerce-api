<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

use Illuminate\Support\Collection;
use JsonSerializable;

class Address implements JsonSerializable
{
    public function __construct(
        public string $type,
        public ?bool $isShielded,
        public ?string $fullAddress,
        public ?string $streetName,
        public ?string $houseNumber,
        public ?string $houseNumberAddition,
        public ?string $houseLetter,
        public ?string $addressSupplement,
        public ?string $postalCode,
        public ?int $poBox,
        public ?string $city,
        public ?string $country,
        public ?string $region,
        public ?Collection $geoData,
    ) {

    }

    public static function fromResponse(array $responseData): Address
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
            region: $responseData['region'] ?? null,
            geoData: $responseData['geoData'] ? collect($responseData['geoData']) : null,
        );
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
