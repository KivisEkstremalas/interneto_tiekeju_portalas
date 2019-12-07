<?php

namespace App\Form;

use App\Entity\Computer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class NewComputerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manufacturer', TextType::class, [
                'label' => 'Gamintojas'
            ])
            ->add('model', TextType::class, [
                'label' => 'Modelis'
            ])
            ->add('serialNo', TextType::class, [
                'label' => 'Serijinis numeris'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Pridėti',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Computer::class,
        ]);
    }
}
