<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfileBranches;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions\UnexpectedResponseException;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic\Branch;
use Illuminate\Support\Collection;

class BaseProfileBranchesResponse
{
    public function __construct(
        protected string $cocNumber,
        protected int $commercialBranches,
        protected int $nonCommercialBranches,
        protected int $totalBranches,
        protected Collection $branches
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
            branches: $branches
        );
    }

    protected static function yesNoToBool($value): bool
    {
        if (! in_array($value, ['Ja', 'Nee'])) {
            throw new UnexpectedResponseException("Unexpected value for Ja/Nee field: $value");
        }

        return $value === 'Ja';
    }
}
