<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Search;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile\BaseProfileRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use Illuminate\Support\Collection;
use JsonSerializable;

class SearchResponseResultItem implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $cocNumber,
        public ?string $rsin,
        public ?string $branchNumber,
        public string $name,
        public Collection $addresses,
        public string $type,
        public bool $active,
        public ?string $canonicalUrl = null,
    ) {
    }

    /**
     * @throws UnexpectedResponseException
     */
    public static function fromResponse($responseData): self
    {
        $responseMap = [
            'kvkNummer' => 'cocNumber',
            'rsin' => 'rsin',
            'naam' => 'name',
            'type' => 'type',
        ];

        $items = [];

        foreach ($responseMap as $apiField => $property) {
            if (! isset($responseData[$apiField])) {
                continue;
            }

            $items[$property] = $responseData[$apiField];
        }

        $responseData['adres'] = $responseData['adres'] ?? [];
        $items['branchNumber'] = $responseData['vestigingsnummer'] ?? null;

        return new self(
            cocNumber: $items['cocNumber'],
            rsin: $items['rsin'] ?? null,
            branchNumber: $items['branchNumber'],
            name: $items['name'],
            addresses: collect($responseData['adres'])->map(function ($address, $type) {
                if ($type == 'binnenlandsAdres') {
                    return new DomesticAddress(
                        streetName: $address['straatnaam'] ?? null,
                        houseNumber: $address['huisnummer'] ?? null,
                        houseLetter: $address['huisLetter'] ?? null,
                        poBoxNumber: $address['postbusnummer'] ?? null,
                        postalCode: $address['postcode'] ?? null,
                        city: $address['plaats']
                    );
                } else {
                    return new ForeignAddress(
                        $address['straat'],
                        $address['postcode'].' '.$address['plaats'],
                        $address['land']
                    );
                }
            }),
            type: $items['type'],
            active: self::yesNoToBool($responseData['actief'] ?? 'Ja'),
            canonicalUrl: collect($responseData['links'])->firstWhere('rel', 'self')['href'] ?? null,
        );
    }

    /**
     * @throws UnexpectedResponseException
     */
    public static function yesNoToBool($value): bool
    {
        if (! in_array($value, ['Ja', 'Nee'])) {
            throw new UnexpectedResponseException("Unexpected value for Ja/Nee field: $value");
        }

        return $value === 'Ja';
    }

    public function createBaseProfileRequest(): BaseProfileRequest
    {
        return (new BaseProfileRequest())->cocNumber($this->cocNumber);
    }
}
