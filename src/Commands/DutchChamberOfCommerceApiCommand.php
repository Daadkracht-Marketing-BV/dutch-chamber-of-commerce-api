<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Commands;

use Illuminate\Console\Command;

class DutchChamberOfCommerceApiCommand extends Command
{
    public $signature = 'dutch-chamber-of-commerce-api';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
