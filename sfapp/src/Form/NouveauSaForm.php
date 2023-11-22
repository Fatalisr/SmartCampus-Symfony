<?php

namespace App\Form;


use App\Entity\Room;
use App\Entity\SA;
use App\Repository\RoomRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


use Symfony\Component\OptionsResolver\OptionsResolver;

class NouveauSaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('name', TextType::class , [
                'label' => 'Nom du SA',
                'attr' => [
                    'placeholder' => 'Nom du SA',
                ],

            ])
            ->add('currentRoom', EntityType::class, [
                'class' => Room::class,
                'query_builder' => function (RoomRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->leftJoin('App\Entity\SA', 's', 'WITH', 's.currentRoom = r')
                        ->where('s.id IS NULL') // Exclude rooms with foreign key in sa table
                        ->orderBy('r.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Pas de salle', // Default or null choice label
                'required' => false, // Allow null values
            ]);
            //->add('save', SubmitType::class, ['label' => 'Creer un SA']);
    }
}
