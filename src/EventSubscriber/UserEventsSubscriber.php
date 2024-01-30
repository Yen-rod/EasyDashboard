<?php

declare(strict_types=1);

namespace App\EventSubscriber;


use App\Entity\User;
use App\Event\UserEmailForSendEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class UserEventsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            UserEmailForSendEvent::USER_CREATE_ACTION => 'onVerifyEmail',
            UserEmailForSendEvent::USER_RESET_ACTION => 'onResetEmail'
        ];
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function onVerifyEmail(UserEmailForSendEvent $event): void
    {
        $this->emailForSignUp(
            $event->getUser(),
            'emails/signup.html.twig'
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onResetEmail(UserEmailForSendEvent $event): void
    {
        $userForReset = $event->getUser();

        $this->entityManager->persist($userForReset->reset());
        $this->entityManager->flush();

        $this->emailForReset(
            $event->getUser(),
            'emails/users/reset-password/linkToResetPassword.html.twig'
        );
    }


    /**
     * @throws TransportExceptionInterface
     */
    private function emailForSignUp(User $userForSignUp, string $template = null) : void
    {
        $email = (new TemplatedEmail())
            ->from('no-responder@tuapp.com')
            ->to(new Address($userForSignUp->getEmail()))
            ->subject('Se necesita verificar email')

            // path of the Twig template to render
            ->htmlTemplate($template)
            ->context([
                'user' => $userForSignUp
            ]);

        $this->mailer->send($email);

    }

    /**
     * @throws TransportExceptionInterface
     */
    private function emailForReset(User $userForReset, string $template = null) : void
    {
        $email = (new TemplatedEmail())
            ->from('no-responder@tuapp.com')
            ->to(new Address($userForReset->getEmail()))
            ->subject('Se ha solicitado un cambio de clave')

            // path of the Twig template to render
            ->htmlTemplate($template)
            ->context([
                'user' => $userForReset
            ]);

        $this->mailer->send($email);

    }
}
