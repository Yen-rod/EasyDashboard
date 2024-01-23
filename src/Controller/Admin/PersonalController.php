<?php

namespace App\Controller\Admin;

use App\Entity\Cliente;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/clientes/show/{clientId}', name: '_clientes', requirements: ['clientId' => '\d+'], methods: ["GET"])]
    public function cliente(
        #[MapEntity(mapping: ['clientId' => 'id'])]Cliente $cliente
    ): Response
    {
        return $this->render("clientes/show.html.twig",[
            'cliente' => $cliente,
        ]);
    }

    #[Route('/clientes/crear', name: '_clientes_nuevo', methods: ["GET","POST"])]
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


    #[Route('/clientes/listar/ajax', name: '_clientes_listado_ajax', methods: ["GET"])]
    public function clientesAjax(ClienteRepository $clienteRepository): JsonResponse
    {
        $request = $this->requestStack->getCurrentRequest();

        $draw = $request->query->get('draw');
        $start = $request->query->get('start');
        $length = $request->query->get('length');

        $clientesPaginated = $clienteRepository->paginate($start,$length);

        return $this->json([
            'draw' => $draw,
            'recordsTotal' => $clientesPaginated->count(),
            'recordsFiltered' => $clientesPaginated->count(),
            'data' => array_map(function ($cliente){
                /** @var Cliente $cliente */
                return [
                    $cliente->getId(),
                    $cliente->getNif(),
                    $cliente->getNombre(),
                    $cliente->getPersonaContacto(),
                    $cliente->getCreatedAt()->format('Y-m-d H:i'),
                    $cliente->getUpdatedAt()->format('Y-m-d H:i')
                ];
            }, $clientesPaginated->getQuery()->getResult())
        ]);
    }


    #[Route('/clientes/listar', name: '_clientes_listado', methods: ["GET"])]
    public function clientes(ClienteRepository $clienteRepository): Response
    {
        return $this->render("clientes/list.html.twig",[
            'clientes' => $clienteRepository->findAll()
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
