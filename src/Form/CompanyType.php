<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Ort;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'placeholder' => 'Name der Firma',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse ',
                'attr' => [
                    'placeholder' => 'Strasse und Nr',
                ],
            ])
            ->add('ort_id', EntityType::class, [
                'label' => 'Ort ',
                'class' => Ort::class,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Company::class,
        ]);
    }
}
