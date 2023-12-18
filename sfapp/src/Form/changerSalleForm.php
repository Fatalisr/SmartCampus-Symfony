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


class changerSalleForm extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('newRoom', EntityType::class, [
                'class' => Room::class,
                'query_builder' => function (RoomRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->leftJoin('App\Entity\SA', 's', 'WITH', 's.currentRoom = r')
                        ->where('s.id IS NULL') // Exclude rooms with foreign key in sa table
                        ->orderBy('r.name', 'ASC');
                },
                'choice_label' => 'name',
                'required' => false, // Allow null values
            ])
            ->add('sa_id', HiddenType::class, [
                'mapped' => false, // Ne pas mapper ce champ à l'entité SA
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Oui',
            ]);

    }
}
