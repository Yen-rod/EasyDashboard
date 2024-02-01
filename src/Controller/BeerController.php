<?php

namespace App\Controller;

use App\Repository\BeerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BeerController extends AbstractController
{
    #[Route('/beers', name: 'app_beer')]
    public function index(BeerRepository $beerRepository): Response
    {
        return $this->render('dashboard/beers/list.html.twig', [
            'beers' => $beerRepository->findAll(),
        ]);
    }
}
