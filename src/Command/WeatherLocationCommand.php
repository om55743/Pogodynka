<?php

namespace App\Command;

use App\Entity\Forecast;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'weather:location', description: 'Shows weather forecast for a location by its ID')]
class WeatherLocationCommand
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Location ID')] int $id,
    ): int {
        $location = $this->locationRepository->find($id);

        if ($location === null) {
            $io->error(sprintf('Location with ID %d not found.', $id));

            return Command::FAILURE;
        }

        $forecasts = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s, %s', $location->getName(), $location->getCountry()));
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
