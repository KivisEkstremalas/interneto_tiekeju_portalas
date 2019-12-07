<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Data',
                'format' => 'dd-MM-yyyy',
                'days' => range(1, 1)
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Suma'
            ])
            ->add('paid', ChoiceType::class, [
                'choices' => [
                    'Sumokėta' => true,
                    'Nesumokėta' => false
                ],
                'label' => 'Ar sumokėta'
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
            'data_class' => Payment::class,
        ]);
    }
}
