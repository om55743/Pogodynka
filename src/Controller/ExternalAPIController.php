<?php

namespace App\Controller;

use App\Form\ExternalAPIType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ExternalAPIController extends AbstractController
{
    #[Route('/externalapi', name: 'app_external_api', methods: ['GET', 'POST'])]
    public function index(Request $request, HttpClientInterface $httpClient): Response
    {
        $form = $this->createForm(ExternalAPIType::class);
        $form->handleRequest($request);

        $weatherData = null;
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];

            // Call the Open-Meteo API
            $apiUrl = sprintf(
                'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&daily=temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=UTC',
                $latitude,
                $longitude,
            );

            try {
                $response = $httpClient->request('GET', $apiUrl);
                $weatherData = $response->toArray();
            } catch (ExceptionInterface $e) {
                $error = 'Could not fetch data from Open-Meteo: ' . $e->getMessage();
            }
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form,
            'weather_data' => $weatherData,
            'error' => $error,
        ]);
    }
}
