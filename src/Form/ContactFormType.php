<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => '*Prénom'],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => '*Nom'],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => '*Email'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Téléphone'],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => false,
                'placeholder' => '*Sujet',
                'choices' => [
                    'Débarrassage' => 'Débarrassage',
                    'Brocante' => 'Brocante',
                    'Surcyclage' => 'Surcyclage',
                    'Autre' => 'Autre',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => '*Votre message', 'rows' => 4],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ]);
    }
}
