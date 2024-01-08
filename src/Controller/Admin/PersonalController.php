<?php

namespace App\Controller\Admin;

use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/personal', name: 'personal')]
class PersonalController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack)
    {}

    #[Route('/clientes/{clientId}', name: '_clientes', requirements: ['clientId' => '\d+'], methods: ["GET"])]
    public function clientes(string $clientId): Response
    {
        return $this->render("clientes/index.html.twig",[
            'clientId' => $clientId,
        ]);
    }

    #[Route('/clientes/nuevo', name: '_clientes_nuevo', methods: ["GET","POST"])]
    public function nuevo_cliente(): Response
    {
        $form = $this->createForm(ClienteType::class);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if($form->isSubmitted() && $form->isValid()){

            $cliente = $form->getData();
            $this->entityManager->persist($cliente);
            $this->entityManager->flush();

            $this->addFlash('success', 'el cliente se añadió sin ploblemas');

            return $this->redirectToRoute('personal_clientes_nuevo');

        }

        return $this->render("clientes/nuevo/index.html.twig",[
            'form' => $form->createView(),
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
