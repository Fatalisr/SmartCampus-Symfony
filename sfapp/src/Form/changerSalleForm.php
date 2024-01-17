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
                        ->leftJoin('App\Entity\SA', 's2', 'WITH', 's2.oldRoom = r')
                        ->where('s.id IS NULL and (s2.id IS NULL or (s2.state != :state and s2.state != :state2))' ) // Exclude rooms with foreign key in sa table
                        ->setParameter('state', "A_INSTALLER")
                        ->setParameter('state2', "INACTIF")
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
