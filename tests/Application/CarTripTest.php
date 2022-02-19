<?php

namespace App\Tests\Application;

use App\Application\CarTrip;
use App\Application\Exception\EntityNotFoundException;
use App\Application\Exception\NotEnoughFuelException;
use App\Domain\Car\Car;
use App\Domain\Car\CarRepositoryInterface;
use App\Domain\City\City;
use App\Domain\City\CityRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CarTripTest extends TestCase
{
    private CarRepositoryInterface $carRepository;
    private CityRepositoryInterface $cityRepository;
    private CarTrip $carTrip;

    protected function setUp(): void
    {
        $this->carRepository = $this->createMock(CarRepositoryInterface::class);
        $this->cityRepository = $this->createMock(CityRepositoryInterface::class);
        $this->carTrip = new CarTrip($this->carRepository, $this->cityRepository);
    }

    /**
     * @test
     */
    public function whenCarNotFoundThenAnExceptionIsThrown(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Entity not found');

        $carUuid = '5f219c62-ab97-41ad-ac29-7cf3d4162819';
        $cityUuid = '26066b7a-dfc7-46e6-952b-d7d87e899ff0';

        ($this->carTrip)($carUuid, $cityUuid);
    }

    /**
     * @test
     */
    public function whenCarHasNotEnoughFuelAnExceptionIsThrown(): void
    {
        $carUuid = '5f219c62-ab97-41ad-ac29-7cf3d4162819';
        $cityUuid = '26066b7a-dfc7-46e6-952b-d7d87e899ff0';

        $car = new Car(Uuid::fromString($carUuid), 1);
        $this->carRepository
            ->expects($this->once())
            ->method('find')
            ->with($carUuid)
            ->willReturn($car);

        $city = new City(Uuid::fromString($carUuid), 'Berlin', 1000);
        $this->cityRepository
            ->expects($this->once())
            ->method('find')
            ->with($cityUuid)
            ->willReturn($city);

        $this->expectException(NotEnoughFuelException::class);
        $this->expectExceptionMessage('Car with uuid 5f219c62-ab97-41ad-ac29-7cf3d4162819 has not enough fuel to reach Berlin');

        ($this->carTrip)($carUuid, $cityUuid);
    }

    /**
     * @test
     */
    public function whenCarHasEnoughFuelWeCanMakeATrip(): void
    {
        $carUuid = '5f219c62-ab97-41ad-ac29-7cf3d4162819';
        $cityUuid = '26066b7a-dfc7-46e6-952b-d7d87e899ff0';

        $car = new Car(Uuid::fromString($carUuid), 1001);
        $this->carRepository
            ->expects($this->once())
            ->method('find')
            ->with($carUuid)
            ->willReturn($car);
        $this->carRepository
            ->expects($this->once())
            ->method('save')
            ->with($car);

        $city = new City(Uuid::fromString($carUuid), 'Berlin', 1000);
        $this->cityRepository
            ->expects($this->once())
            ->method('find')
            ->with($cityUuid)
            ->willReturn($city);

        ($this->carTrip)($carUuid, $cityUuid);
    }
}
