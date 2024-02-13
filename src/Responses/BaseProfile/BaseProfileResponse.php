<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\SbiActivity;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Tradename;
use Illuminate\Support\Collection;

class BaseProfileResponse extends ApiResponse
{
    public function __construct(
        protected string $cocNumber,
        protected bool $nonMailingIndicator,
        protected string $name,
        protected string $formalDateOfRecord,
        protected int $totalNumberOfEmployees,
        protected string $statutoryName,
        protected Collection $tradeNames,
        protected Collection $sbiActivities,
        protected array $_embedded
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
        $formalDateOfRecord = $responseData['formeleRegistratiedatum'];
        $totalNumberOfEmployees = $responseData['totaalWerkzamePersonen'];
        $statutoryName = $responseData['statutaireNaam'];
        $tradeNames = collect($responseData['handelsnamen'])
            ->map(
                fn ($tradename) => new Tradename(
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
            formalDateOfRecord: $formalDateOfRecord,
            totalNumberOfEmployees: $totalNumberOfEmployees,
            statutoryName: $statutoryName,
            tradeNames: $tradeNames,
            sbiActivities: $sbiActivities,
            _embedded: $_embedded
        );
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

    public function getCocNumber(): mixed
    {
        return $this->cocNumber;
    }

    public function getNonMailingIndicator(): bool
    {
        return $this->nonMailingIndicator;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormalDateOfRecord(): string
    {
        return $this->formalDateOfRecord;
    }

    public function getTotalNumberOfEmployees(): int
    {
        return $this->totalNumberOfEmployees;
    }

    public function getStatutoryName(): string
    {
        return $this->statutoryName;
    }

    public function getTradeNames(): Collection
    {
        return $this->tradeNames;
    }

    public function getSbiActivities(): Collection
    {
        return $this->sbiActivities;
    }

    public function getEmbedded(): array
    {
        return $this->_embedded;
    }
}
