<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use Illuminate\Support\Collection;

class SearchResponse extends ApiResponse
{
    /**
     * @var Collection<SearchResponseResultItem>
     */
    protected Collection $results;

    public function __construct(array $response)
    {
        $this->parseResponse($response);
    }

    protected function parseResponse(array $response): void
    {
        $this->results = collect($response['resultaten'])->map(function ($result) {
            return SearchResponseResultItem::fromResponse($result);
        });
    }

    public function getResults(): Collection
    {
        return $this->results;
    }
}

class SearchResponseResultItem
{
    public function __construct(
        protected string $cocNumber,
        protected ?string $rsin,
        protected ?string $branchNumber,
        protected string $name,
        protected Collection $addresses,
        protected string $type,
        protected bool $active
    ) {
    }

    public static function fromResponse($responseData): self
    {
        $responseMap = [
            'kvkNummer' => 'cocNumber',
            'rsin' => 'rsin',
            'naam' => 'name',
            'type' => 'type',
        ];

        $items = [];

        foreach ($responseMap as $apiField => $property) {
            if (! isset($responseData[$apiField])) {
                continue;
            }

            $items[$property] = $responseData[$apiField];
        }

        $responseData['adres'] = $responseData['adres'] ?? [];
        $items['branchNumber'] = $responseData['vestigingsnummer'] ?? null;

        return new self(
            cocNumber: $items['cocNumber'],
            rsin: $items['rsin'] ?? null,
            branchNumber: $items['branchNumber'],
            name: $items['name'],
            addresses: collect($responseData['adres'])->map(function ($address, $type) {
                if ($type == 'binnenlandsAdres') {
                    return new DomesticAddress(
                        streetName: $address['straatnaam'],
                        houseNumber: $address['huisnummer'] ?? null,
                        houseLetter: $address['huisLetter'] ?? null,
                        poBoxNumber: $address['postbusnummer'] ?? null,
                        postalCode: $address['postcode'] ?? null,
                        city: $address['plaats']
                    );
                } else {
                    return new ForeignAddress(
                        $address['straat'],
                        $address['postcode'].' '.$address['plaats'],
                        $address['land']
                    );
                }
            }),
            type: $items['type'],
            active: self::yesNoToBool($responseData['actief'] ?? 'Ja')
        );
    }

    public function getCocNumber(): string
    {
        return $this->cocNumber;
    }

    public function getRsin(): ?string
    {
        return $this->rsin;
    }

    public function getBranchNumber(): ?string
    {
        return $this->branchNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function serialize(): array
    {
        return [
            'cocNumber' => $this->cocNumber,
            'rsin' => $this->rsin,
            'branchNumber' => $this->branchNumber,
            'name' => $this->name,
            'addresses' => $this->addresses->map(function ($address) {
                return $address->serialize();
            })->toArray(),
            'type' => $this->type,
            'active' => $this->active,
        ];
    }

    public static function yesNoToBool($value): bool
    {
        if (! in_array($value, ['Ja', 'Nee'])) {
            throw new UnexpectedResponseException("Unexpected value for Ja/Nee field: $value");
        }

        return $value === 'Ja';
    }

    public function __serialize(): array
    {
        return $this->serialize();
    }
}

class DomesticAddress
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
}

class ForeignAddress
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
}
