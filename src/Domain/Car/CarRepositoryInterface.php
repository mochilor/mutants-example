<?php

namespace App\Domain\Car;

interface CarRepositoryInterface
{
    public function find(string $uuid): ?Car;
    public function save(Car $car): void;
}
