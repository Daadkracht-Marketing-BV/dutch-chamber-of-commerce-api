<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Responses\Generic;

class Tradename implements \JsonSerializable
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

    public function serialize(): array
    {
        return [
            'name' => $this->name,
            'order' => $this->order,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->serialize();
    }

    public function __serialize(): array
    {
        return $this->serialize();
    }
}
