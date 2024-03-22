<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfileBranches;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Branch;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Traits\SerializableResponse;
use Illuminate\Support\Collection;
use JsonSerializable;

class BaseProfileBranchesResponse extends ApiResponse implements JsonSerializable
{
    use SerializableResponse;

    public function __construct(
        public string $cocNumber,
        public int $commercialBranches,
        public int $nonCommercialBranches,
        public int $totalBranches,
        public Collection $branches,
        public ?string $canonicalUrl = null,
    ) {

    }

    public static function fromResponse($responseData): BaseProfileBranchesResponse
    {
        $cocNumber = $responseData['kvkNummer'];
        $commercialBranches = $responseData['aantalCommercieleVestigingen'] ?? 0;
        $nonCommercialBranches = $responseData['aantalNietCommercieleVestigingen'] ?? 0;
        $totalBranches = $responseData['totaalAantalVestigingen'] ?? 0;
        $branches = collect($responseData['vestigingen'])->map(
            fn ($branch) => new Branch(
                $branch['vestigingsnummer'],
                $branch['eersteHandelsnaam'],
                static::yesNoToBool($branch['indHoofdvestiging'] ?? 'Nee'),
                static::yesNoToBool($branch['indAdresAfgeschermd'] ?? 'Ja'),
                static::yesNoToBool($branch['indCommercieleVestiging'] ?? 'Nee'),
                $branch['volledigAdres']
            )
        );

        return new self(
            cocNumber: $cocNumber,
            commercialBranches: $commercialBranches,
            nonCommercialBranches: $nonCommercialBranches,
            totalBranches: $totalBranches,
            branches: $branches,
            canonicalUrl: collect($responseData['links'])->firstWhere('rel', 'self')['href'] ?? null,
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
