<?php

namespace App\Application;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Exception\NotEnoughFuelException;
use App\Domain\Car\CarRepositoryInterface;
use App\Domain\City\CityRepositoryInterface;

class CarTrip
{
    private CarRepositoryInterface $carRepository;
    private CityRepositoryInterface $cityRepository;

    public function __construct(
        CarRepositoryInterface $carRepository,
        CityRepositoryInterface $cityRepository
    ) {
        $this->carRepository = $carRepository;
        $this->cityRepository = $cityRepository;
    }

    public function __invoke(string $carUuid, string $cityUuid): void
    {
        $car = $this->carRepository->find($carUuid);
        $city = $this->cityRepository->find($cityUuid);

        if (!$car || !$city) {
            throw new EntityNotFoundException();
        }

        if ($car->fuel() < $city->distance()) {
            throw new NotEnoughFuelException($car->uuid(), $city->name());
        }

        for ($n = 0; $n < $city->distance(); $n++) {
            $car->run();
        }

        $this->carRepository->save($car);
    }
}
