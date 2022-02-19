<?php

namespace App\Domain\City;

use Ramsey\Uuid\UuidInterface;

class City
{
    private UuidInterface $uuid;
    private string $name;
    private int $distance;

    public function __construct(UuidInterface $uuid, string $name, int $distance)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->distance = $distance;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function distance(): int
    {
        return $this->distance;
    }
}
