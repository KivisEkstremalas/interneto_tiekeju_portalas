<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Provider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Vardas',
                'required' => true
            ])
            ->add('surname', TextType::class, [
                'label' => 'Pavarde',
                'required' => true
            ])
            ->add('provider', EntityType::class, [
                'label' => 'Tiekėjas',
                'required' => true,
                'multiple' => false,
                'class' => Provider::class,
                'choice_label' => 'Name'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Keisti rolę',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
