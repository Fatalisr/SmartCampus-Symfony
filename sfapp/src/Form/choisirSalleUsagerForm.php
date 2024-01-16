<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Room;
use App\Repository\RoomRepository;
use function Sodium\add;

class choisirSalleUsagerForm extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'query_builder' => function (RoomRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->leftJoin('App\Entity\SA', 's', 'WITH', 's.currentRoom = r')
                        ->where('s.state = :actif')
                        ->setParameter('actif', "ACTIF")
                        ->orderBy('r.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez une salle'
            ]);
    }

}