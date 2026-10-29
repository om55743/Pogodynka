<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{name}', name: 'app_weather', requirements: ['country' => '[A-Za-z]{2}'])]
    public function name(
        #[MapEntity(expr: 'repository.findByCountryAndName(country, name)')]
        Location $location,
        ForecastRepository $repository
    ): Response {
        $forecasts = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'forecasts' => $forecasts,
        ]);
    }
}
