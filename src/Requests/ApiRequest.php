<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use Psr\Http\Client\ClientInterface;

abstract class ApiRequest
{
    abstract public function get(): ApiResponse;
}
