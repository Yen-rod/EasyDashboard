<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserResetPasswordFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password',TextType::class,[
                'mapped'=>false
            ])
            ->add('newPassword',PasswordType::class,[
                'mapped'=>false
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT,function (FormEvent $formEvent){
            $form = $formEvent->getForm();
            if ($form->get('password')->getData() !== $form->get('newPassword')->getData()) {
                $form->get('password')->addError(
                    new FormError('Passwords do not match')
                );
            }

        });


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
