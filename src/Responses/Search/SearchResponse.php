<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\Search\SearchRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use Illuminate\Support\Collection;
use JsonSerializable;

class SearchResponse extends ApiResponse implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public Collection $results,
        public int $page,
        public int $resultsPerPage,
        public int $totalResults,
        public SearchRequest $originalRequest
    ) {
    }

    public static function fromResponse(array $response, SearchRequest $originalRequest): self
    {
        $results = collect($response['resultaten'])->map(function ($result) use ($response) {
            return SearchResponseResultItem::fromResponse($result, canonicalUrl: collect($response['links'] ?? [])->firstWhere('rel', 'self')['href'] ?? null);
        });

        return new self(
            results: $results,
            page: $response['pagina'],
            resultsPerPage: $response['resultatenPerPagina'],
            totalResults: $response['totaal'],
            originalRequest: $originalRequest
        );
    }

    public function getNextPageNumber(?int $currentPage = null): ?int
    {
        $nextPage = ($currentPage ?? $this->page) + 1;
        $totalPages = ceil($this->totalResults / $this->resultsPerPage);

        return $nextPage <= $totalPages ? $nextPage : null;
    }

    /**
     * @throws ApiException
     */
    public function resultIterator($maxAdditionalPagesFetched = null): \Generator
    {
        $ourPage = $this->page;
        $currentPage = $ourPage;
        $additionalPagesFetched = 0;

        foreach ($this->results as $result) {
            yield $result;
        }

        if ($maxAdditionalPagesFetched === 0) {
            return;
        }

        // are there any more pages?
        while ($nextPage = $this->getNextPageNumber($currentPage)) {
            $clone = clone $this->originalRequest;
            $clone->page($nextPage);

            $currentPage = $nextPage;
            $result = $clone->fetch();

            $additionalPagesFetched++;

            foreach ($result->results as $result) {
                yield $result;
            }

            if (
                $maxAdditionalPagesFetched !== null &&
                $additionalPagesFetched >= $maxAdditionalPagesFetched
            ) {
                return;
            }
        }
    }
}
