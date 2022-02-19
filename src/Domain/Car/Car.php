<?php

namespace App\Domain\Car;

use Ramsey\Uuid\UuidInterface;

class Car
{
    private UuidInterface $uuid;
    private int $fuel;

    public function __construct(UuidInterface $uuid, int $fuel)
    {
        $this->uuid = $uuid;
        $this->fuel = $fuel;
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    public function fuel(): int
    {
        return $this->fuel;
    }

    public function run(): void
    {
        $this->fuel--;
    }
}
