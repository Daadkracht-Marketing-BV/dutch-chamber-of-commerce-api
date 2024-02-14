<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BranchProfile;

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

    public function __construct($testMode = false)
    {
        $this->testMode = $testMode;
    }

    public function setBranchNumber(string $branchNumber): self
    {
        $this->branchNumber = $branchNumber;

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

    public function get(): BranchProfileResponse
    {
        $response = $this->getResponse();

        return BranchProfileResponse::fromResponse(
            responseData: $response->json()
        );
    }

    // alias for all setters without the set prefix
    public function branchNumber(string $branchNumber): self
    {
        return $this->setBranchNumber($branchNumber);
    }

    public function testMode(bool $testMode): self
    {
        $this->testMode = $testMode;
        return $this;
    }

    public function requestGeoData(bool $requestGeoData): self
    {
        $this->requestGeoData = $requestGeoData;
        return $this;
    }
}
