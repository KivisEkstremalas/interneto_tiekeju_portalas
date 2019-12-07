<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NewPurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('purchase', TextType::class, [
                'label' => 'Pirkinys'
            ])
            ->add('date', DateType::class, [
                'label' => 'Data',
                'format' => 'dd-MM-yyyy',
                'years' => range(2014, date('Y'))
            ])
            ->add('amount', NumberType:: class, [
                'label' => 'Suma'
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
            'data_class' => Purchase::class,
        ]);
    }
}
