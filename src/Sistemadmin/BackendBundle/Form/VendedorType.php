<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VendedorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('dni', 'text', array(
                'label' => 'DNI',
                'attr' => array('class' => 'form-control digits', 'placeholder' => 'DNI','maxLength'=>'8'),'required' => false
            ))
            ->add('nombre', 'text', array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Nombre',)
            ))
            
            ->add('direccion', 'text', array(
                'label' => 'Dirección',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Dirección',),'required' => false
            ))
            ->add('telefono', 'text', array(
                'label' => 'Teléfono',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Teléfono','maxLength'=>'30'),'required' => false
            ))
            ->add('celular', 'text', array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Celular','maxLength'=>'30'),'required' => false
            ))
           
            ->add('email', 'email', array(
                'attr' => array('class' => 'form-control email', 'placeholder' => 'Email',),'required' => false
            ))            
            ->add('file');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Vendedor'
        ));
    }
}
