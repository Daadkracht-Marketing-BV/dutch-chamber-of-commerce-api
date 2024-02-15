<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\ApiException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\ApiRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile\BaseProfileResponse;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

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

    public function cocNumber(string $cocNumber): self
    {
        $this->cocNumber = $cocNumber;

        return $this;
    }

    public function requestGeoData(bool $requestGeoData): self
    {
        $this->requestGeoData = $requestGeoData;

        return $this;
    }

    public function getSubRequest(): string
    {
        return '/' . $this->cocNumber;
    }

    /**
     * @throws ApiException
     * @throws UnexpectedResponseException
     */
    public function fetch(): BaseProfileResponse
    {
        $response = $this->getResponse();

        $responseData = $response->json();
        if (ApiException::isException($responseData)) {
            throw ApiException::fromResponse($responseData);
        }

        return BaseProfileResponse::fromResponse(
            responseData: $responseData
        );
    }

    protected function getResponse(): Response|PromiseInterface
    {
        $apiKey = config('dutch-chamber-of-commerce-api.api_key');

        return Http::withOptions(
            // set the CA bundle to the one provided by the package
            [
                //                'verify' => dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'cacert.pem',
                'verify' => false,
            ]
        )
            ->withHeader('apikey', $apiKey)
            ->get(
                url: $this->getApiEndpoint().'/'.$this->getSubRequest(),
                query: $this->getQueryString()
            );
    }
}
