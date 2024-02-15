<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use Illuminate\Support\Collection;
use JsonSerializable;

class SearchResponse extends ApiResponse implements JsonSerializable
{
    public function __construct(
        public Collection $results
    ) {
    }

    public static function fromResponse(array $response): self
    {
        $results = collect($response['resultaten'])->map(function ($result) {
            return SearchResponseResultItem::fromResponse($result);
        });

        return new self(results: $results);
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
