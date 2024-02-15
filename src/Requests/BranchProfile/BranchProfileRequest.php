<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiException;
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

    protected bool $requestGeoData;

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

    public function getApiEndpoint(): string
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function getResponse(): Response|PromiseInterface
    {
        $apiKey = config('dutch-chamber-of-commerce-api.api_key');

        return Http::withOptions(
            [
                'verify' => false,
            ]
        )->withHeaders([
            'apikey' => $apiKey,
        ])->get($this->getApiEndpoint().'/'.$this->branchNumber);
    }

    /**
     * @throws ApiException
     * @throws UnexpectedResponseException
     */
    public function fetch(): BranchProfileResponse
    {
        $response = $this->getResponse();

        $responseData = $response->json();
        if (ApiException::isException($responseData)) {
            throw ApiException::fromResponse($responseData);
        }

        return BranchProfileResponse::fromResponse(
            responseData: $responseData
        );
    }
}
