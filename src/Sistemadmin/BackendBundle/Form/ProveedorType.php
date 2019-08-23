<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProveedorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array(
                'label'=>'Nombre',
                'attr' => array('class' => 'form-control ', 'placeholder' => 'Nombre',),
            ))
            ->add('numerodocumento','number', array(
                    'required' => false,
                'label'=>'Número de documento',
                'attr' => array('class' => 'form-control digits', 'placeholder' => 'Número de documento',),
                ))
            ->add('ruc','number', array(
                    'required' => false,
                'attr' => array('class' => 'form-control digits', 'placeholder' => 'Ruc','maxLength'=>'11'),
            ))
            ->add('razonsocial','text', array(
                    'required' => false,
                'label'=>'Razón social',
                'attr' => array('class' => 'form-control ', 'placeholder' => 'Razón social',),
            ))
            ->add('direccion','text', array(
                    'required' => false,
                'label'=>'Dirección',
                'attr' => array('class' => 'form-control ', 'placeholder' => 'Número de documento',),
            ))
            ->add('correo','email', array(
                    'required' => false,
                'attr' => array('class' => 'form-control email', 'placeholder' => 'Correo',),

            ))
            ->add('telefono',null, array(
                    'required' => false,
                'label'=>'Teléfono',
                'attr' => array('class' => 'form-control ', 'placeholder' => 'Teléfono ','maxLength'=>'30'),
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Proveedor'
        ));
    }
}
