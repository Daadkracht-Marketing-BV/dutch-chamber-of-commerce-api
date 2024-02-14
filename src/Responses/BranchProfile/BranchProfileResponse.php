<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BranchProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Address;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\SbiActivity;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Tradename;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class BranchProfileResponse extends ApiResponse
{
    public function __construct(
        protected string $branchNumber,
        protected string $cocNumber,
        protected ?string $rsin,
        protected bool $nonMailingIndicator,
        protected string $formalDateOfRecord,
        protected Collection $materialRegistration,
        protected string $statutoryName,
        protected string $firstTradeName,
        protected bool $isMainBranch,
        protected bool $isCommercialBranch,
        protected int $fulltimeEmployees,
        protected int $parttimeEmployees,
        protected int $totalEmployees,
        protected Collection $tradeNames,
        protected Collection $addresses,
        protected Collection $websites,
        protected Collection $sbiActivities,
    ) {

    }

    public static function fromResponse(array $responseData): self
    {
        $responseMap = [
            'vestigingsnummer' => 'branchNumber',
            'kvkNummer' => 'cocNumber',
            'rsin' => 'rsin',
            'statutaireNaam' => 'statutoryName',
            'eersteHandelsnaam' => 'firstTradeName',
            'formeleRegistratiedatum' => 'formalDateOfRecord',
            'indHoofdVestiging' => 'isMainBranch',
            'indCommercieleVestiging' => 'isCommercialBranch',
            'voltijdWerkzamePersonen' => 'fulltimeEmployees',
            'deeltijdWerkzamePersonen' => 'parttimeEmployees',
            'totaalWerkzamePersonen' => 'totalEmployees',
            'handelsnamen' => 'tradeNames',
            'sbiActiviteiten' => 'sbiActivities',
        ];

        $items = [];

        foreach ($responseMap as $apiField => $property) {
            if (! isset($responseData[$apiField])) {
                continue;
            }

            $items[$property] = $responseData[$apiField];
        }

        $items['nonMailingIndicator'] = static::yesNoToBool($responseData['indNonMailing']);
        $items['isMainBranch'] = static::yesNoToBool($responseData['indHoofdvestiging']);
        $items['isCommercialBranch'] = static::yesNoToBool($responseData['indCommercieleVestiging']);

        $typeMap = [
            'datumAanvang' => 'dateStart',
            'datumEinde' => 'dateEnd',
        ];

        $items['materialRegistration'] = collect($responseData['materieleRegistratie'])->map(
            fn ($date, $type) => [
                'type' => $typeMap[$type] ?? $type,
                'date' => Date::make($date, 'Ymd'),
            ]
        );

        $items['addresses'] = collect($responseData['adressen'])->map(
            fn ($address) => new Address(
                type: $address['type'] ?? null,
                isShielded: static::yesNoToBool($address['indAdresAfgeschermd'] ?? 'Nee'),
                fullAddress: $address['volledigAdres'] ?? null,
                streetName: $address['straatnaam'] ?? null,
                houseNumber: $address['huisnummer'] ?? null,
                houseNumberAddition: $address['huisnummerToevoeging'] ?? null,
                houseLetter: $address['huisletter'] ?? null,
                addressSupplement: $address['toevoegingAdres'] ?? null,
                postalCode: $address['postcode'] ?? null,
                poBox: $address['postbusnummer'] ?? null,
                city: $address['plaats'] ?? null,
                country: $address['land'] ?? null,
                region: $address['regio'] ?? null,
                geoData: isset($address['geoData']) ? collect($address['geoData']) : null,
            )
        );

        $items['websites'] = collect($responseData['websites'])->map(
            fn ($website) => $website
        );

        $items['tradeNames'] = collect($responseData['handelsnamen'])->sort(
            fn ($a, $b) => $a['volgorde'] <=> $b['volgorde']
        )->map(
            fn ($tradeName) => new Tradename(
                name: $tradeName['naam'],
                order: $tradeName['volgorde']
            )
        );

        $items['sbiActivities'] = collect($responseData['sbiActiviteiten'])->map(
            fn ($activity) => new SbiActivity(
                sbiCode: $activity['sbiCode'],
                sbiDescription: $activity['sbiOmschrijving'],
                isMainActivity: static::yesNoToBool($activity['indHoofdactiviteit'])
            )
        );

        return new self(
            branchNumber: $items['branchNumber'],
            cocNumber: $items['cocNumber'],
            rsin: $items['rsin'],
            nonMailingIndicator: $items['nonMailingIndicator'],
            formalDateOfRecord: $items['formalDateOfRecord'],
            materialRegistration: $items['materialRegistration'],
            statutoryName: $items['statutoryName'],
            firstTradeName: $items['firstTradeName'],
            isMainBranch: $items['isMainBranch'],
            isCommercialBranch: $items['isCommercialBranch'],
            fulltimeEmployees: $items['fulltimeEmployees'],
            parttimeEmployees: $items['parttimeEmployees'],
            totalEmployees: $items['totalEmployees'],
            tradeNames: $items['tradeNames'],
            addresses: $items['addresses'],
            websites: $items['websites'],
            sbiActivities: $items['sbiActivities'],
        );
    }

    public function getBranchNumber(): string
    {
        return $this->branchNumber;
    }

    public function getCocNumber(): string
    {
        return $this->cocNumber;
    }

    public function getRsin(): ?string
    {
        return $this->rsin;
    }

    public function getNonMailingIndicator(): bool
    {
        return $this->nonMailingIndicator;
    }

    public function getFormalDateOfRecord(): string
    {
        return $this->formalDateOfRecord;
    }

    public function getMaterialRegistration(): Collection
    {
        return $this->materialRegistration;
    }

    public function getStatutoryName(): string
    {
        return $this->statutoryName;
    }

    public function getFirstTradeName(): string
    {
        return $this->firstTradeName;
    }

    public function getIsMainBranch(): bool
    {
        return $this->isMainBranch;
    }

    public function getIsCommercialBranch(): bool
    {
        return $this->isCommercialBranch;
    }

    public function getFulltimeEmployees(): int
    {
        return $this->fulltimeEmployees;
    }

    public function getParttimeEmployees(): int
    {
        return $this->parttimeEmployees;
    }

    public function getTotalEmployees(): int
    {
        return $this->totalEmployees;
    }

    public function getTradeNames(): Collection
    {
        return $this->tradeNames;
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function getWebsites(): Collection
    {
        return $this->websites;
    }

    public function getSbiActivities(): Collection
    {
        return $this->sbiActivities;
    }

    public function serialize(): array
    {
        return [
            'branchNumber' => $this->branchNumber,
            'cocNumber' => $this->cocNumber,
            'rsin' => $this->rsin,
            'nonMailingIndicator' => $this->nonMailingIndicator,
            'formalDateOfRecord' => $this->formalDateOfRecord,
            'materialRegistration' => $this->materialRegistration,
            'statutoryName' => $this->statutoryName,
            'firstTradeName' => $this->firstTradeName,
            'isMainBranch' => $this->isMainBranch,
            'isCommercialBranch' => $this->isCommercialBranch,
            'fulltimeEmployees' => $this->fulltimeEmployees,
            'parttimeEmployees' => $this->parttimeEmployees,
            'totalEmployees' => $this->totalEmployees,
            'tradeNames' => $this->tradeNames,
            'addresses' => $this->addresses,
            'websites' => $this->websites,
            'sbiActivities' => $this->sbiActivities,
        ];
    }

    public function __serialize(): array
    {
        return $this->serialize();
    }

    /**
     * @throws UnexpectedResponseException
     */
    protected static function yesNoToBool($value): bool
    {
        if (! in_array($value, ['Ja', 'Nee'])) {
            throw new UnexpectedResponseException("Unexpected value for Ja/Nee field: $value");
        }

        return $value === 'Ja';
    }
}
