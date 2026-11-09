<?php

namespace App\Command;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'weather:city', description: 'Shows weather forecast for a city by country code and city name')]
class WeatherCityCommand
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $forecasts = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($forecasts === []) {
            $io->warning(sprintf('No forecasts found for %s, %s.', $city, $country));

            return Command::SUCCESS;
        }

        $io->writeln(sprintf('Location: %s, %s', $city, $country));
        $io->table(
            ['Date', 'Temperature (°C)', 'Humidity (%)', 'Chance for precipitation (%)', 'Wind (m/s)'],
            array_map(fn (Forecast $f) => [
                $f->getDate()->format('Y-m-d'),
                $f->getTemperature(),
                $f->getHumidity(),
                $f->getRain(),
                $f->getWind(),
            ], $forecasts),
        );

        return Command::SUCCESS;
    }
}
