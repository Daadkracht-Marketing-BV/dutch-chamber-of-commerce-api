<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\Search;

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

    public function setCocNumber(?string $cocNumber): self
    {
        $this->query['kvkNummer'] = $cocNumber;

        $this->unsetIfNull('kvkNummer');

        return $this;
    }

    public function setRsin(?string $rsin): self
    {
        $this->query['rsin'] = $rsin;

        $this->unsetIfNull('rsin');

        return $this;
    }

    public function setBranchNumber(?string $branchNumber): self
    {
        $this->query['vestigingsnummer'] = $branchNumber;

        $this->unsetIfNull('vestigingsnummer');

        return $this;
    }

    public function setName(?string $name): self
    {
        $this->query['naam'] = $name;

        $this->unsetIfNull('naam');

        return $this;
    }

    public function setStreetName(?string $streetName): self
    {
        $this->query['straatnaam'] = $streetName;

        $this->unsetIfNull('straatnaam');

        return $this;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->query['huisnummer'] = $houseNumber;

        $this->unsetIfNull('huisnummer');

        return $this;
    }

    public function setHouseLetter(?string $houseLetter): self
    {
        $this->query['huisletter'] = $houseLetter;

        $this->unsetIfNull('huisletter');

        return $this;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->query['postcode'] = $postalCode;

        $this->unsetIfNull('postcode');

        return $this;
    }

    public function setPlace(?string $place): self
    {
        $this->query['plaats'] = $place;

        $this->unsetIfNull('plaats');

        return $this;
    }

    public function setPoBoxNumber(?string $poBoxNumber): self
    {
        $this->query['postbusnummer'] = $poBoxNumber;

        $this->unsetIfNull('postbusnummer');

        return $this;
    }

    public function setType(array|string|null $type): self
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

        $this->unsetIfNull('type');

        return $this;
    }

    public function setIncludeInactiveRegistrations(bool $includeInactiveRegistrations): self
    {
        $this->query['InclusiefInactieveRegistraties'] = $includeInactiveRegistrations;

        $this->unsetIfNull('InclusiefInactieveRegistraties');

        return $this;
    }

    public function setPage(int $page): self
    {
        $this->query['pagina'] = $page;

        $this->unsetIfNull('pagina');

        return $this;
    }

    public function setResultsPerPage(int $resultsPerPage): self
    {
        $this->query['resultatenPerPagina'] = $resultsPerPage;

        $this->unsetIfNull('resultatenPerPagina');

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

    protected function unsetIfNull(string $key): void
    {
        if (is_null($this->query[$key])) {
            unset($this->query[$key]);
        }
    }

    public function get(): SearchResponse
    {
        //        $apiKey = config('dutch-chamber-of-commerce-api.api_key');

        $response = Http::withOptions(
            // set the CA bundle to the one provided by the package
            [
                //                'verify' => dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'cacert.pem',
                'verify' => false,
            ]
        )
//            ->withHeader('apikey', config('dutch-chamber-of-commerce-api.api_key'))
            ->get(
                url: $this->getApiEndpoint(),
                query: $this->getQueryString()
            );

        // pass the json to the response object
        return new SearchResponse(
            $response->json()
        );
    }
}
