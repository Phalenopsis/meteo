<?php

namespace App\Controller;

use App\Entity\Meteo;
use App\Form\MeteoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FetchMeteo;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MeteoController extends AbstractController
{
    #[Route('/', name: 'app_meteo')]
    public function index(Request $request): Response
    {

        $content = null;
        $lat = null;
        $lon = null;
        $meteo = new Meteo();
        $form = $this->createForm(MeteoType::class, $meteo);

        $form->handleRequest($request);
        if ($form->isSubmitted()){

            return $this->redirectToRoute('app_display_meteo', ['town' => $meteo->getTown()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meteo/index.html.twig', [
                'form' => $form,
        ]);
    }

    #[Route('/meteo/{town}', name: 'app_display_meteo')]
    public function displayMeteo(string $town, HttpClientInterface $client)
    {
        $key = $this->getParameter('meteo_api_key');
        $meteoApi = new FetchMeteo($client, $key);
        [$lat, $lon] = $meteoApi->fetchTownCoords($town);
        $content = $meteoApi->fetchMeteoAt($lat, $lon);


        return $this->render('meteo/display_meteo.html.twig', [
            'lat' => $lat,
            'lon' => $lon,
            'content' => $content,
        ]);
    }
}
