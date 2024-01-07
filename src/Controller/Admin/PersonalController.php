<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/personal', name: 'personal')]
class PersonalController extends AbstractController
{
    #[Route('/clientes/{clientId}', name: '_clientes', requirements: ['clientId' => '\d+'], methods: ["GET"])]
    public function clientes(string $clientId): Response
    {
        return $this->render("clientes/index.html.twig",[
            'clientId' => $clientId,
        ]);
    }

    #[Route('/empleados/{empleadosId}', name: '_empleados', requirements: ['empleadosId' => '\d+'], methods: ["GET"])]
    public function empleados(string $empleadosId): Response
    {
        return $this->render("clientes/index.html.twig",[
            'empleadosId' => $empleadosId
        ]);
    }
}
