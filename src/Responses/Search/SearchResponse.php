<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use Illuminate\Support\Collection;
use JsonSerializable;

class SearchResponse extends ApiResponse implements JsonSerializable
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

    public function serialize(): array
    {
        return [
            'results' => $this->results->map(function ($result) {
                return $result->serialize();
            })->toArray(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->serialize();
    }
}
