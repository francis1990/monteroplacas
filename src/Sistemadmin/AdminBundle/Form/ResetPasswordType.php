<?php

namespace Sistemadmin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('password','repeated',array(
                'type'=>'password',
                'invalid_message'=>'Debe coincidir el valor de la clave',
                'options'=>array('attr'=>array('class'=>'form-control')),
                'first_options'=>array('label'=> 'Nueva Clave:'),
                'second_options'=>array('label'=> 'Repita Clave:')
            ))

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\AdminBundle\Entity\Usuario'
        ));
    }
}
