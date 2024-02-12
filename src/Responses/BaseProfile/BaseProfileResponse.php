<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\SbiActivity;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Tradename;
use Illuminate\Support\Collection;

class BaseProfileResponse extends ApiResponse
{
    protected mixed $cocNumber;
    protected bool $nonMailingIndicator;
    protected string $name;
    protected string $formalDateOfRecord;
    // TODO add materieleRegistratie fields
    protected int $totalNumberOfEmployees;
    protected int $statutoryName;
    protected Collection $tradeNames;
    protected Collection $sbiActivities;

    protected $_embedded = [];

    /**
     * @throws UnexpectedResponseException
     */
    public function __construct($responseData)
    {
        $this->cocNumber = $responseData['kvkNummer'];
        $this->nonMailingIndicator = $this->yesNoToBool(
            $responseData['indNonMailing']
        );
        $this->name = $responseData['naam'];
        $this->formalDateOfRecord = $responseData['formeleRegistratieDatum'];
        $this->totalNumberOfEmployees = $responseData['totaalWerkzamePersonen'];
        $this->statutoryName = $responseData['statutaireNaam'];
        $this->tradeNames = collect($responseData['handelsnamen'])
            ->map(
                fn($tradename) => new Tradename(
                    $tradename['naam'],
                    $tradename['volgorde']
                ));
        $this->sbiActivities = collect($responseData['sbiActiviteiten'])
            ->map(
                fn($sbiActivity) => new SbiActivity(
                    $sbiActivity['sbiCode'],
                    $sbiActivity['sbiOmschrijving'],
                    $this->yesNoToBool($sbiActivity['indHoofdactiviteit'])
                ));

        $this->_embedded = $responseData['_embedded'];
    }

    /**
     * @throws UnexpectedResponseException
     */
    private function yesNoToBool($value): bool
    {
        if (!in_array($value, ['Ja', 'Nee'])) {
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

    public function getStatutoryName(): int
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
