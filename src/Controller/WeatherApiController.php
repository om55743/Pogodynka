<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        if (!in_array($format, ['json', 'csv'], true)) {
            return $this->json(
                ['error' => 'Invalid format. Allowed values: json, csv.'],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            $contentType = $format === 'csv'
                ? 'text/plain; charset=UTF-8'
                : 'application/json';

            return $this->render('weather_api/index.' . $format . '.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ], new Response(headers: ['Content-Type' => $contentType]));
        }

        if ($format === 'csv') {
            $lines = ['city,country,date,celsius,fahrenheit,humidity,chance_for_precipitation,wind'];

            foreach ($measurements as $m) {
                $lines[] = implode(',', [
                    $city,
                    $country,
                    $m->getDate()->format('Y-m-d'),
                    $m->getTemperature(),
                    $m->getFahrenheit(),
                    $m->getHumidity(),
                    $m->getRain(),
                    $m->getWind(),
                ]);
            }

            return new Response(
                implode("\n", $lines),
                Response::HTTP_OK,
                ['Content-Type' => 'text/plain; charset=UTF-8'],
            );
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn (Forecast $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getTemperature(),
                'fahrenheit' => $m->getFahrenheit(),
                'humidity' => $m->getHumidity(),
                'chance_for_precipitation' => $m->getRain(),
                'wind' => $m->getWind(),
            ], $measurements),
        ]);
    }
}
