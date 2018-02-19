<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Ort;
use App\Entity\Log;
use App\Entity\Service;
use App\Entity\Timetracker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimetrackerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('company', EntityType::class, [
		        'label' => 'Firma ',
		        'class' => Company::class,
		        'attr' => [
		        	'onchange' => 'getContactsForCompany(this)'
		        ],
		        'placeholder' => '--- Bitte Auswählen ---',
	        ])
//	        ->add('contact', EntityType::class, [
//		        'label' => 'Kontakt ',
//		        'class' => Contact::class,
//	        ])
	        ->add('service', EntityType::class, [
		        'label' => 'Service ',
		        'class' => Service::class,
	        ])
            ->add('comment', TextType::class,[
            	'label' => 'Kommentar ',
	            'required' => false,
	            'attr' => [
		            'placeholder' => 'Kommentar',
	            ],
            ])
/*	        ->add('exist', TextType,[
		        'required' => false,
		        'attr' => [
		        	'type' => 'hidden',
			        'value' => 'false',
		        ],
	        ])*/
	        ->add('Starten', SubmitType::class)
        ;
	    $formModifier = function (FormInterface $form, Company $company = null) {
	    	$form->add('contact', EntityType::class, [
			    'label' => 'Kontakt ',
		    	'class' => Contact::class,
			    'choices' => $company ? $company->getContacts() : [],
		    ]);
	    };
	
	    $builder->addEventListener(
		    FormEvents::PRE_SET_DATA,
		    function (FormEvent $event) use ($formModifier) {
			    $data = $event->getData();
			    $formModifier($event->getForm(), $data ? $data->getCompany() : null);
		    }
	    );
	
	    $builder->get('company')->addEventListener(
		    FormEvents::POST_SUBMIT,
		    function (FormEvent $event) use ($formModifier) {
			    // It's important here to fetch $event->getForm()->getData(), as
			    // $event->getData() will get you the client data (that is, the ID)
			    $sport = $event->getForm()->getData();
			
			    // since we've added the listener to the child, we'll have to pass on
			    // the parent to the callback functions!
			    $formModifier($event->getForm()->getParent(), $sport);
		    }
	    );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            'data_class' => Timetracker::class,
        ]);
    }
}
