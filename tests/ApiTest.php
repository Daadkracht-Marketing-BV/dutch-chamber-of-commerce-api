<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Facades\DutchChamberOfCommerceApi;
use DaadkrachtMarketing\DutchChamberOfCommerceApi\Requests\Search\SearchRequest;

it('can instantiate a search request using the facade', function () {
    $searchRequest = DutchChamberOfCommerceApi::searchRequest();
    expect($searchRequest)->toBeInstanceOf(SearchRequest::class);
});
