<?php

use DaadkrachtMarketing\DutchChamberOfCommerceApi\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function fixture(string $name): array
{
    $file = __DIR__ . "/Fixtures/{$name}.json";

    if (!file_exists($file)) {
        throw new InvalidArgumentException("Fixture {$file} does not exist");
    }

    return json_decode(
        json: file_get_contents($file),
        associative: true
    );
}
