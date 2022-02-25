<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class TestApi extends AbstractController
{
    #[Route('test', name: 'testApi')]
    public function index(CallApiService $callApiService): Response
    {
        $aCommune = $callApiService->getCommuneNameByDepartement("63");


        return $this->renderForm('api/index.html.twig', [
            'title' => '',
            'pageTitle' => 'Test',
            'communes' => $aCommune
        ]);
    }

    #[Route('test2', name: 'testApi2')]
    public function airport(CallApiService $callApiService)
    {

        $airports = $callApiService->getAirports();
        dd($airports["states"]);


        $aAirport = $callApiService->getArrivalbyAirportCode("LFPG");

        dd($aAirport);
    }
}
