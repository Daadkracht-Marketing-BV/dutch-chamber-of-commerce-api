<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\ApiRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile\BaseProfileResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search\SearchResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Psr\Http\Client\ClientInterface;

class BaseProfileRequest extends ApiRequest
{
    protected string $liveEndpoint = 'https://api.kvk.nl/api/v1/basisprofielen';

    protected string $testEndpoint = 'https://api.kvk.nl/test/api/v1/basisprofielen';

    private bool $testMode;

    protected string $cocNumber;

    protected bool $requestGeoData = false;

    public function __construct($testMode = false)
    {
        $this->testMode = $testMode;
    }

    public function getApiEndpoint(): string
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getQueryString(): array
    {
        return [
            'geoData' => $this->requestGeoData ? 'True' : 'False',
        ];
    }

    public function setCocNumber(string $cocNumber): self
    {
        $this->cocNumber = $cocNumber;

        return $this;
    }

    public function setRequestGeoData(bool $requestGeoData): self
    {
        $this->requestGeoData = $requestGeoData;

        return $this;
    }

    public function getSubRequest(): string
    {
        return '/' . $this->cocNumber;
    }

    public function get(): BaseProfileResponse
    {
        $response = $this->getResponse();

        return BaseProfileResponse::fromResponse(
            responseData: $response->json()
        );
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    protected function getResponse(): \Illuminate\Http\Client\Response|\GuzzleHttp\Promise\PromiseInterface
    {
        $apiKey = config('dutch-chamber-of-commerce-api.api_key');

        $response = Http::withOptions(
        // set the CA bundle to the one provided by the package
            [
                'verify' => dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'cacert.pem'
            ]
        )
            ->withHeader('apikey', $apiKey)
            ->get(
                url: $this->getApiEndpoint() . '/' . $this->getSubRequest(),
                query: $this->getQueryString()
            );
        return $response;
    }
}
