<?php

namespace App\Form;


use App\Entity\Room;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)

            ->add('currentRoom', EntityType::class,
                [
                    'class' => Room::class,
                    'query_builder' => function (RoomRepository $er): QueryBuilder {
                        return $er->createQueryBuilder('r')
                            ->orderBy('r.id', 'ASC');
                    },
                    'choice_label' => 'name',
                ])
            ->add('save', SubmitType::class, ['label' => 'Creer un SA']);
    }

}
