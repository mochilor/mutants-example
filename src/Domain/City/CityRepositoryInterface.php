<?php

namespace App\Domain\City;

interface CityRepositoryInterface
{
    public function find(string $uuid): ?City;
}
