<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

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
            return new SearchResponseResultItem($result);
        });
    }

    public function getResults(): Collection
    {
        return $this->results;
    }

}

class SearchResponseResultItem
{
    protected string $cocNumber;

    protected ?string $rsin = null;

    protected string $branchNumber;

    protected string $name;

    /**
     * @var Collection<DomesticAddress|ForeignAddress>
     */
    protected Collection $addresses;

    protected string $type;

    protected bool $active = true; // the API sometimes does not return this field, so we default to true

    public function __construct(array $result)
    {
        $this->addresses = collect(); // TODO: implement address parsing

        $responseMap = [
            'kvkNummer' => 'cocNumber',
            'rsin' => 'rsin',
            'vestigingsnummer' => 'branchNumber',
            'naam' => 'name',
            'type' => 'type',
        ];

        foreach ($responseMap as $apiField => $property) {
            if (!isset($result[$apiField])) {
                continue;
            }
            $this->$property = $result[$apiField];
        }
    }

    public function getCocNumber(): string
    {
        return $this->cocNumber;
    }

    public function getRsin(): ?string
    {
        return $this->rsin;
    }

    public function getBranchNumber(): string
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
}

class DomesticAddress
{
}

class ForeignAddress
{
}
