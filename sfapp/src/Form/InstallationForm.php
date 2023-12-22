<?php
// src/Form/LoginFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class InstallationForm extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('report', TextareaType::class, [
                'label' => "Retour d'intervention",
                'attr' => ['rows' => 10,'cols'=>60, 'placeholder' => 'Ecrivez votre rapport ici'],
            ])
            ->add('valid', SubmitType::class, [
                'label' => "VALIDER L'INSTALLATION"
            ]);

    }
}
