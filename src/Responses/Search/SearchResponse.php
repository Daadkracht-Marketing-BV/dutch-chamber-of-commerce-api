<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use Illuminate\Support\Collection;
use JsonSerializable;

class SearchResponse extends ApiResponse implements JsonSerializable
{
    use SerializableResponse;

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

    public function createBaseProfileRequest($cocNumber): BaseProfileRequest
    {
        return (new BaseProfileRequest())->cocNumber($cocNumber);
    }
}
