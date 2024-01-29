<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserEmailForSendEvent;
use App\Form\UserEmailFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    )
    {}
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
    }

    #[Route('/user/new', name: 'add_new_user')]
    public function addNewUser(): Response
    {
        $usuarioForm = $this->createForm(UserFormType::class);
        $usuarioForm->handleRequest($this->requestStack->getCurrentRequest());
        if($usuarioForm->isSubmitted() && $usuarioForm->isValid()){

            $this->entityManager->persist($usuarioForm->getData());
            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(
                new UserEmailForSendEvent($usuarioForm->getData()),
                UserEmailForSendEvent::USER_CREATE_ACTION
            );

            $this->addFlash('success','El usuario se ha registrado con éxito');
        }

        return $this->render('users/signup/signup.html.twig', [
            'form' => $usuarioForm->createView(),
        ]);
    }


    #[Route('/user/activate/{token}', name: 'app_user_activate')]
    public function activar(#[MapEntity(mapping: ['token' => 'token'])]User $userForActivate): Response
    {
        $userForActivate->setActivo(User::USER_IS_ACTIVE);
        $userForActivate->setToken(null);

        $this->entityManager->persist($userForActivate);
        $this->entityManager->flush();

        $this->addFlash('success','Usuario activado');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/forgot/pass', name: 'app_user_forgot',methods: ["GET","POST"])]
    public function olvido(): Response
    {
        $usuarioEmailForm = $this->createForm(UserEmailFormType::class);
        $usuarioEmailForm->handleRequest($this->requestStack->getCurrentRequest());

        if($usuarioEmailForm->isSubmitted() && $usuarioEmailForm->isValid()){

            $emailForFind = $usuarioEmailForm->get('email')->getData();

            $userForResetPassword = $this->entityManager->getRepository(User::class)
                ->findOneBy([
                    'email' => $emailForFind,
                    'activo' => User::USER_IS_ACTIVE
                ]);

            if(null === $userForResetPassword){
                $this->addFlash('error','El usuario no existe.');
            }

            $this->eventDispatcher->dispatch(
                new UserEmailForSendEvent($userForResetPassword),
                UserEmailForSendEvent::USER_RESET_ACTION
            );

            $this->addFlash('success','Se ha enviado un email de cambio de clave.');

            return $this->redirectToRoute('app_user_forgot');
        }

        return $this->render('users/reset/forgot.html.twig',[
            'usuarioEmailForm' => $usuarioEmailForm->createView()
        ]);

    }

    #[Route('/cambiar/clave/{token}', name: 'cambiar_clave')]
    public function cambioClave(#[MapEntity(mapping: ['token' => 'token'])]User $userForChangePassword): Response {
        dd($userForChangePassword);
    }

}
