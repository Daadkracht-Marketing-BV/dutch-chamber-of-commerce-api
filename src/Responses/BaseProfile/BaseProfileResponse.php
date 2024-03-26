<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfileBranches\BaseProfileBranchesRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\SbiActivity;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\TradeName;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use JsonSerializable;

class BaseProfileResponse extends ApiResponse implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $cocNumber,
        public bool $nonMailingIndicator,
        public string $name,
        public ?Carbon $formalDateOfRecord,
        public int $totalNumberOfEmployees,
        public ?string $statutoryName,
        public Collection $tradeNames,
        public Collection $sbiActivities,
        public array $_embedded,
        public ?string $canonicalUrl = null,
    ) {

    }

    /**
     * @throws UnexpectedResponseException
     */
    public static function fromResponse($responseData): self
    {
        $cocNumber = $responseData['kvkNummer'];
        $nonMailingIndicator = static::yesNoToBool(
            $responseData['indNonMailing']
        );
        $name = $responseData['naam'];
        if (isset($responseData['formeleRegistratiedatum'])) {
            $formalDateOfRecord = Date::make($responseData['formeleRegistratiedatum']);
        }
        $totalNumberOfEmployees = $responseData['totaalWerkzamePersonen'];
        $statutoryName = $responseData['statutaireNaam'] ?? null;
        $tradeNames = collect($responseData['handelsnamen'])
            ->map(
                fn ($tradename) => new TradeName(
                    $tradename['naam'],
                    $tradename['volgorde']
                ));
        $sbiActivities = collect($responseData['sbiActiviteiten'])
            ->map(
                fn ($sbiActivity) => new SbiActivity(
                    $sbiActivity['sbiCode'],
                    $sbiActivity['sbiOmschrijving'],
                    static::yesNoToBool($sbiActivity['indHoofdactiviteit'])
                ));

        $_embedded = $responseData['_embedded'];

        return new self(
            cocNumber: $cocNumber,
            nonMailingIndicator: $nonMailingIndicator,
            name: $name,
            formalDateOfRecord: $formalDateOfRecord ?? null,
            totalNumberOfEmployees: $totalNumberOfEmployees,
            statutoryName: $statutoryName,
            tradeNames: $tradeNames,
            sbiActivities: $sbiActivities,
            _embedded: $_embedded,
            canonicalUrl: collect($responseData['links'])->firstWhere('rel', 'self')['href'] ?? null,
        );
    }

    public function createBaseProfileBranchesRequest(): BaseProfileBranchesRequest
    {
        return (new BaseProfileBranchesRequest())->cocNumber($this->cocNumber);
    }

    /**
     * @throws UnexpectedResponseException
     */
    protected static function yesNoToBool($value): bool
    {
        if (! in_array($value, ['Ja', 'Nee'])) {
            throw new UnexpectedResponseException(
                sprintf('Unexpected value for Ja/Nee field: %s', $value)
            );
        }

        return $value === 'Ja';
    }
}
