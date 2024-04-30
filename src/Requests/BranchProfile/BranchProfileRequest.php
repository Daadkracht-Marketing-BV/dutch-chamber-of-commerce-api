<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiHttpException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\ApiRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BranchProfile\BranchProfileResponse;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class BranchProfileRequest extends ApiRequest
{
    protected string $liveEndpoint = 'https://api.kvk.nl/api/v1/vestigingsprofielen';

    protected string $testEndpoint = 'https://api.kvk.nl/test/api/v1/vestigingsprofielen';

    protected bool $testMode;

    protected string $branchNumber;

    protected bool $requestGeoData = false;

    public function __construct($testMode = false)
    {
        $this->testMode = $testMode;
    }

    public function branchNumber(string $branchNumber): self
    {
        $this->branchNumber = $branchNumber;

        return $this;
    }

    public function requestGeoData(bool $requestGeoData): self
    {
        $this->requestGeoData = $requestGeoData;

        return $this;
    }

    // withGeo alias for above function
    public function withGeo(bool $requestGeoData = true): self
    {
        return $this->requestGeoData($requestGeoData);
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

    protected function getResponse(): Response|PromiseInterface
    {
        $apiKey = config('dutch-chamber-of-commerce-api.api_key');

        return Http::withOptions(
            [
                'verify' => config('dutch-chamber-of-commerce-api.verify'),
            ]
        )->withHeaders([
            'apikey' => $apiKey,
        ])->get(
            url: $this->getApiEndpoint().'/'.$this->branchNumber,
            query: $this->getQueryString()
        );
    }

    /**
     * @throws ApiException
     * @throws UnexpectedResponseException
     * @throws ApiHttpException
     */
    public function fetch(): BranchProfileResponse
    {
        $response = $this->getResponse();

        if (ApiHttpException::isException($response)) {
            throw ApiHttpException::fromResponse($response);
        }

        $responseData = $response->json();
        if (ApiException::isException($responseData)) {
            throw ApiException::fromResponse($responseData);
        }

        return BranchProfileResponse::fromResponse(
            responseData: $responseData
        );
    }
}
