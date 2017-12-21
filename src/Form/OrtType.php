<?php

namespace App\Form;

use App\Entity\Ort;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrtType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder

	        ->add('PLZ', NumberType::class, [
		    'label' => 'PLZ',
	
	        ])
	        ->add('ort', TextType::class, [
			    'label' => 'Ort ',
		    ])
		    ->add('submit', SubmitType::class)
	    ;
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([// uncomment if you want to bind to a class
            'data_class' => Ort::class,
        ]);
    }
}