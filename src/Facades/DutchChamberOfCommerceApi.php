<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DaadkrachtMarketing\DutchChamberOfCommerceApi\DutchChamberOfCommerceApi
 */
class DutchChamberOfCommerceApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \DaadkrachtMarketing\DutchChamberOfCommerceApi\DutchChamberOfCommerceApi::class;
    }
}
