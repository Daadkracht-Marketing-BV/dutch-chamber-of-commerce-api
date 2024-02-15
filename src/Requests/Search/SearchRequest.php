<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\ApiRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search\SearchResponse;
use Illuminate\Support\Facades\Http;

class SearchRequest extends ApiRequest
{
    protected array $query = [];

    protected string $liveEndpoint = 'https://api.kvk.nl/api/v2/zoeken';

    protected string $testEndpoint = 'https://api.kvk.nl/test/api/v2/zoeken';

    private bool $testMode;

    public function __construct($testMode = false)
    {
        $this->testMode = $testMode;
    }

    public function cocNumber(?string $cocNumber): self
    {
        $this->query['kvkNummer'] = $cocNumber;

        return $this;
    }

    public function rsin(?string $rsin): self
    {
        $this->query['rsin'] = $rsin;

        return $this;
    }

    public function branchNumber(?string $branchNumber): self
    {
        $this->query['vestigingsnummer'] = $branchNumber;

        return $this;
    }

    public function name(?string $name): self
    {
        $this->query['naam'] = $name;

        return $this;
    }

    public function streetName(?string $streetName): self
    {
        $this->query['straatnaam'] = $streetName;

        return $this;
    }

    public function houseNumber(?string $houseNumber): self
    {
        $this->query['huisnummer'] = $houseNumber;

        return $this;
    }

    public function houseLetter(?string $houseLetter): self
    {
        $this->query['huisletter'] = $houseLetter;

        return $this;
    }

    public function postalCode(?string $postalCode): self
    {
        $this->query['postcode'] = $postalCode;

        return $this;
    }

    public function place(?string $place): self
    {
        $this->query['plaats'] = $place;

        return $this;
    }

    public function poBoxNumber(?string $poBoxNumber): self
    {
        $this->query['postbusnummer'] = $poBoxNumber;

        return $this;
    }

    public function type(array|string|null $type): self
    {
        /*
         * KVK Api documentation states:
         *
         * Filter by type: main branch, branch, and/or legal entity.
         * Combine multiple by using ‘&’.
         */
        if (is_array($type)) {
            $type = implode('&', $type);
        }

        $this->query['type'] = $type;

        return $this;
    }

    public function includeInactiveRegistrations(bool $includeInactiveRegistrations): self
    {
        $this->query['InclusiefInactieveRegistraties'] = $includeInactiveRegistrations;

        return $this;
    }

    public function page(int $page): self
    {
        $this->query['pagina'] = $page;

        return $this;
    }

    public function resultsPerPage(int $resultsPerPage): self
    {
        $this->query['resultatenPerPagina'] = $resultsPerPage;

        return $this;
    }

    public function getQueryString(): string
    {
        // convert query to collection
        $query = collect($this->query);

        // remove null values (strict) and convert all boolean values to true/false strings
        $query = $query
            ->filter(fn ($value) => ! is_null($value))
            ->map(fn ($value) => is_bool($value) ? ($value ? 'true' : 'false') : $value);

        return http_build_query($query->toArray());
    }

    public function getApiEndpoint(): string
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @throws ApiException
     */
    public function fetch(): SearchResponse
    {
        $apiKey = config('dutch-chamber-of-commerce-api.api_key');

        $response = Http::withOptions(
            // set the CA bundle to the one provided by the package
            [
                'verify' => dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'cacert.pem',
            ]
        )
            ->withHeaders(['apikey' => $apiKey])
            ->get(
                url: $this->getApiEndpoint(),
                query: $this->getQueryString()
            );

        // pass the json to the response object
        $responseJson = $response->json();

        if (ApiException::isException($responseJson)) {
            throw ApiException::fromResponse($responseJson);
        }

        return SearchResponse::fromResponse(
            response: $responseJson
        );
    }
}
