<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\ApiRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile\BaseProfileResponse;

class BaseProfileRequest extends ApiRequest
{
    public function get(): BaseProfileResponse
    {
        return new BaseProfileResponse();
    }
}
