<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\BaseProfile;

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\ApiRequest;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\BaseProfile\BaseProfileResponse;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\ApiResponse;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Client\ClientInterface;

class BaseProfileRequest extends ApiRequest
{

    public function get(): BaseProfileResponse
    {
        return new BaseProfileResponse();
    }
}
