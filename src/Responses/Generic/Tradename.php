<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

class Tradename
{
    public function __construct(protected string $name, protected int $order)
    {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
