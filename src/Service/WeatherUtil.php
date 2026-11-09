<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Forecast;
use App\Entity\Location;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly ForecastRepository $forecastRepository,
    ) {
    }

    /**
     * @return Forecast[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->forecastRepository->findBy(
            ['location' => $location],
            ['Date' => 'ASC'],
        );
    }

    /**
     * @return Forecast[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'Country' => $countryCode,
            'Name' => $city,
        ]);

        if ($location === null) {
            return [];
        }

        return $this->getWeatherForLocation($location);
    }
}
