<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use Illuminate\Support\Collection;
use JsonSerializable;

class SearchResponse extends ApiResponse implements JsonSerializable
{
    public function __construct(protected Collection $results)
    {
    }

    public static function fromResponse(array $response): self
    {
        $results = collect($response['resultaten'])->map(function ($result) {
            return SearchResponseResultItem::fromResponse($result);
        });

        return new self(results: $results);
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
