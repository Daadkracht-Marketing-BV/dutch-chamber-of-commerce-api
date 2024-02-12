<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;

abstract class ApiRequest
{
    abstract public function get(): ApiResponse;
}
