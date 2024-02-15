<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BranchProfile;

use Carbon\Carbon;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Address;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\SbiActivity;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\TradeName;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class BranchProfileResponse extends ApiResponse implements \JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $branchNumber,
        public string $cocNumber,
        public ?string $rsin,
        public bool $nonMailingIndicator,
        public Carbon $formalDateOfRecord,
        public Collection $materialRegistration,
        public string $statutoryName,
        public string $firstTradeName,
        public bool $isMainBranch,
        public bool $isCommercialBranch,
        public int $fulltimeEmployees,
        public int $parttimeEmployees,
        public int $totalEmployees,
        public Collection $tradeNames,
        public Collection $addresses,
        public Collection $websites,
        public Collection $sbiActivities,
    ) {

    }

    /**
     * @throws UnexpectedResponseException
     */
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
                'date' => Date::make($date),
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
            fn ($tradeName) => new TradeName(
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
            formalDateOfRecord: Date::make($items['formalDateOfRecord']),
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
