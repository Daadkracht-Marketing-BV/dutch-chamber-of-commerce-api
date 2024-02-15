<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use Illuminate\Support\Collection;
use JsonSerializable;

class SearchResponseResultItem implements JsonSerializable
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

    /**
     * @throws UnexpectedResponseException
     */
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

    /**
     * @throws UnexpectedResponseException
     */
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

    public function jsonSerialize(): array
    {
        return $this->serialize();
    }
}
